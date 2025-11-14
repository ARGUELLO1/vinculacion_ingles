<?php

namespace App\Livewire\Forms\AdminCoordinador\Documentos;

use App\Models\DocumentoProfesor;
use App\Models\Nivel;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfesoresForm extends Form
{
    #[Validate('required|file|mimes:pdf|max:10240')]
    public $planeacion;

    public $nivelId;
    public $nivel;

    public function rules()
    {
        return [
            'planeacion' => 'required|file|mimes:pdf|max:10240',
        ];
    }

    public function setNivel($nivelId)
    {
        $this->nivelId = $nivelId;
        $this->nivel = Nivel::with('profesor')->findOrFail($nivelId);
    }

    public function subirPlaneacion()
    {
        $this->validate();

        try {
            $profesor = $this->nivel->profesor;

            // Generar nombre del archivo
            $nombreArchivo = 'planeacion_' . time() . '.pdf'; // Agregar timestamp para evitar conflictos

            // Generar ruta de almacenamiento
            $rutaCarpeta = $this->generarRutaCarpeta($profesor, $this->nivel);
            $rutaArchivo = $this->planeacion->storeAs($rutaCarpeta, $nombreArchivo, 'expedientesProfesores');

            // Guardar en la base de datos
            DocumentoProfesor::updateOrCreate(
                [
                    'nivel_id' => $this->nivel->id_nivel,
                    'tipo_doc' => 'planeacion'
                ],
                [
                    'ruta_doc' => $rutaArchivo,
                ]
            );

            $this->resetForm();
            return ['success' => true, 'message' => 'Planeación subida correctamente.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al subir la planeación: ' . $e->getMessage()];
        }
    }

    public function eliminarPlaneacion($documentoId)
    {
        try {
            $documento = DocumentoProfesor::findOrFail($documentoId);

            // Eliminar archivo físico
            if (Storage::disk('expedientesProfesores')->exists($documento->ruta_doc)) { // Cambiado de ruta_archivo a ruta_doc
                Storage::disk('expedientesProfesores')->delete($documento->ruta_doc);
            }

            // Eliminar registro de la base de datos
            $documento->delete();

            return ['success' => true, 'message' => 'Planeación eliminada correctamente.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar la planeación: ' . $e->getMessage()];
        }
    }

    public function descargarPlaneacion($documentoId)
    {
        try {
            $documento = DocumentoProfesor::findOrFail($documentoId);

            if (!Storage::disk('expedientesProfesores')->exists($documento->ruta_doc)) {
                return ['success' => false, 'message' => 'El archivo no existe.'];
            }

            // Generar nombre descriptivo usando información del nivel y profesor
            $profesor = $documento->nivel->profesor;
            $nombreProfesor = $this->limpiarNombreArchivo($profesor->nombre_completo ?? $profesor->nombre);
            $nombreDescarga = "planeacion_{$nombreProfesor}_Nivel_{$documento->nivel->nivel}_Grupo_{$documento->nivel->nombre_grupo}.pdf";

            return [
                'success' => true,
                'download' => Storage::disk('expedientesProfesores')->download($documento->ruta_doc, $nombreDescarga)
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al descargar la planeación: ' . $e->getMessage()];
        }
    }

    public function obtenerInfoDocumento($documentoId)
    {
        try {
            $documento = DocumentoProfesor::findOrFail($documentoId);
            return ['success' => true, 'documento' => $documento];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al obtener información del documento: ' . $e->getMessage()];
        }
    }

    private function generarRutaCarpeta($profesor, $nivel)
    {
        $nombreProfesor = $this->limpiarNombreArchivo($profesor->nombre_completo ?? $profesor->nombre);
        $nombreGrupo = $this->limpiarNombreArchivo($nivel->nombre_grupo);

        return "{$nombreProfesor}/Nivel_{$nivel->nivel}_Grupo_{$nombreGrupo}";
    }

    private function limpiarNombreArchivo($nombre)
    {
        // Reemplazar caracteres especiales y espacios
        $nombre = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nombre);
        return trim($nombre, '_');
    }

    public function resetForm()
    {
        $this->reset('planeacion', 'nivelId', 'nivel');
    }

    public function getNivelInfo()
    {
        if ($this->nivel) {
            return [
                'profesor' => $this->nivel->profesor->nombre_completo ?? $this->nivel->profesor->nombre,
                'nivel_grupo' => $this->nivel->nivel . ' - ' . $this->nivel->nombre_grupo,
                'modalidad' => $this->nivel->modalidad->tipo_modalidad
            ];
        }
        return null;
    }
}
