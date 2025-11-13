<?php

namespace App\Livewire\Forms\AdminCoordinador\Documentos;

use App\Models\Alumno;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
use App\Models\Nivel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ConstanciasForm extends Form
{
    public $files = [];

    public $nivelId;
    public $nivel;

    public function rules()
    {
        return [
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:pdf|max:10240',
        ];
    }

    public function setNivel($nivelId)
    {
        $this->nivelId = $nivelId;
        // Cargar nivel con expedientes y alumnos relacionados
        $this->nivel = Nivel::with(['profesor', 'modalidad', 'expedientes.alumno'])->findOrFail($nivelId);
    }

    public function subirConstancias($alumnoId = null)
    {
        $this->validate();

        try {
            $uploadedCount = 0;
            $noAsignados = [];
            $alumno = $alumnoId ? Alumno::find($alumnoId) : null;

            // Obtener todos los expedientes del nivel con alumnos
            $expedientes = Expediente::where('nivel_id', $this->nivelId)
                ->with('alumno')
                ->get();

            foreach ($this->files as $file) {
                if ($alumno) {
                    // Caso específico de alumno: subir a su carpeta individual
                    $uploadedCount += $this->subirConstanciaParaAlumno($file, $alumno);
                } else {
                    // Caso global: buscar alumno por nombre de archivo
                    $alumnoEncontrado = $this->buscarAlumnoPorArchivo(
                        $file->getClientOriginalName(),
                        $expedientes
                    );

                    if ($alumnoEncontrado) {
                        $uploadedCount += $this->subirConstanciaParaAlumno($file, $alumnoEncontrado);
                    } else {
                        // Guardar en carpeta de no asignados
                        $rutaNoAsignado = $this->subirConstanciaNoAsignada($file);
                        $noAsignados[] = [
                            'archivo' => $file->getClientOriginalName(),
                            'ruta' => $rutaNoAsignado
                        ];
                        Log::warning("No se pudo encontrar alumno para archivo: " . $file->getClientOriginalName());
                    }
                }
            }

            $this->resetForm();

            $mensaje = $uploadedCount . ' constancia(s) subida(s) correctamente.';
            if (count($noAsignados) > 0) {
                $mensaje .= ' ' . count($noAsignados) . ' archivo(s) no se pudieron asignar y se guardaron en la carpeta "No_Asignados".';
            }

            return [
                'success' => true,
                'message' => $mensaje,
                'no_asignados' => $noAsignados
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al subir las constancias: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Subir constancia a carpeta de no asignados
     */
    private function subirConstanciaNoAsignada($file)
    {
        // Generar nombre único para el archivo (mantener nombre original para identificación)
        $nombreArchivo = $file->getClientOriginalName();

        // Generar ruta de almacenamiento para no asignados
        $rutaCarpeta = $this->generarRutaNoAsignados();
        $rutaArchivo = $file->storeAs($rutaCarpeta, $nombreArchivo, 'expedientesNiveles');

        // También podrías guardar en base de datos con un flag especial
        DocumentoNivel::create([
            'nivel_id' => $this->nivel->id_nivel,
            'tipo_doc' => 'constancia_no_asignada',
            'ruta_doc' => $rutaArchivo,
            'nombre_original' => $nombreArchivo, // Guardar nombre original para referencia
        ]);

        return $rutaArchivo;
    }

    /**
     * Generar ruta para archivos no asignados
     */
    private function generarRutaNoAsignados()
    {
        $nivel = $this->nivel->nivel;
        $grupo = $this->limpiarNombreArchivo($this->nivel->nombre_grupo);

        return "Nivel_{$nivel}_Grupo_{$grupo}/Constancias/No_Asignados";
    }

    /**
     * Buscar alumno basado en el nombre del archivo (versión mejorada)
     */
    private function buscarAlumnoPorArchivo($fileName, $expedientes)
    {
        // Extraer posibles identificadores del nombre del archivo
        $nombreLimpio = pathinfo($fileName, PATHINFO_FILENAME);
        $nombreLimpio = strtolower($this->limpiarNombreArchivo($nombreLimpio));

        // Intentar diferentes estrategias de búsqueda
        $estrategias = [
            'matricula_exacta' => function ($alumno, $nombreLimpio) {
                return strpos($nombreLimpio, strtolower($alumno->matricula)) !== false;
            },
            'nombre_completo' => function ($alumno, $nombreLimpio) {
                $nombreAlumnoLimpio = strtolower($this->limpiarNombreArchivo($alumno->nombre_completo));
                return strpos($nombreLimpio, $nombreAlumnoLimpio) !== false ||
                    strpos($nombreAlumnoLimpio, $nombreLimpio) !== false;
            },
            'partes_nombre' => function ($alumno, $nombreLimpio) {
                $nombreAlumnoLimpio = strtolower($this->limpiarNombreArchivo($alumno->nombre_completo));
                $partesNombre = explode('_', $nombreAlumnoLimpio);
                foreach ($partesNombre as $parte) {
                    if (strlen($parte) > 3 && strpos($nombreLimpio, $parte) !== false) {
                        return true;
                    }
                }
                return false;
            }
        ];

        foreach ($expedientes as $expediente) {
            $alumno = $expediente->alumno;
            if (!$alumno) continue;

            foreach ($estrategias as $estrategia) {
                if ($estrategia($alumno, $nombreLimpio)) {
                    Log::info("Alumno encontrado para archivo '{$fileName}': {$alumno->nombre_completo} ({$alumno->matricula})");
                    return $alumno;
                }
            }
        }

        Log::warning("No se encontró alumno para archivo: {$fileName}");
        return null;
    }

    private function subirConstanciaParaAlumno($file, $alumno)
    {
        // Generar nombre único para el archivo (mantener formato consistente)
        $nombreArchivo = 'constancia_' . $this->nivel->nivel . '.pdf';

        // Generar ruta de almacenamiento con la estructura deseada
        $rutaCarpeta = $this->generarRutaCarpeta($alumno);
        $rutaArchivo = $file->storeAs($rutaCarpeta, $nombreArchivo, 'expedientesNiveles');

        // Guardar en la base de datos
        DocumentoNivel::create([
            'nivel_id' => $this->nivel->id_nivel,
            'tipo_doc' => 'constancia',
            'ruta_doc' => $rutaArchivo,
        ]);

        return 1; // Contador de archivos subidos
    }

    public function eliminarConstancia($documentoId)
    {
        try {
            $documento = DocumentoNivel::findOrFail($documentoId);

            // Eliminar archivo físico
            if (Storage::disk('expedientesNiveles')->exists($documento->ruta_doc)) {
                Storage::disk('expedientesNiveles')->delete($documento->ruta_doc);
            }

            // Eliminar registro de la base de datos
            $documento->delete();

            return ['success' => true, 'message' => 'Constancia eliminada correctamente.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar la constancia: ' . $e->getMessage()];
        }
    }

    public function eliminarTodasConstanciasAlumno($alumnoId)
    {
        try {
            $alumno = Alumno::find($alumnoId);
            $rutaCarpetaAlumno = $this->generarRutaCarpeta($alumno);

            $documentos = DocumentoNivel::where('nivel_id', $this->nivelId)
                ->where('tipo_doc', 'constancia')
                ->where('ruta_doc', 'like', $rutaCarpetaAlumno . '%')
                ->get();

            $deletedCount = 0;

            foreach ($documentos as $documento) {
                // Eliminar archivo físico
                if (Storage::disk('expedientesNiveles')->exists($documento->ruta_doc)) {
                    Storage::disk('expedientesNiveles')->delete($documento->ruta_doc);
                }
                $documento->delete();
                $deletedCount++;
            }

            return [
                'success' => true,
                'message' => $deletedCount . ' constancia(s) eliminada(s) correctamente del alumno.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar las constancias: ' . $e->getMessage()
            ];
        }
    }

    public function eliminarTodasConstanciasNivel()
    {
        try {
            $documentos = DocumentoNivel::where('nivel_id', $this->nivelId)
                ->where('tipo_doc', 'constancia')
                ->get();

            $deletedCount = 0;

            foreach ($documentos as $documento) {
                // Eliminar archivo físico
                if (Storage::disk('expedientesNiveles')->exists($documento->ruta_doc)) {
                    Storage::disk('expedientesNiveles')->delete($documento->ruta_doc);
                }
                $documento->delete();
                $deletedCount++;
            }

            return [
                'success' => true,
                'message' => $deletedCount . ' constancia(s) eliminada(s) correctamente del nivel.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar las constancias: ' . $e->getMessage()
            ];
        }
    }

    public function getAlumnosConConteo()
    {
        // Obtener alumnos a través de expedientes del nivel
        $expedientes = Expediente::where('nivel_id', $this->nivelId)
            ->with('alumno')
            ->get();

        $alumnosConConteo = [];

        foreach ($expedientes as $expediente) {
            if ($expediente->alumno) {
                $alumno = $expediente->alumno;

                // Contar constancias del alumno (por ruta de carpeta)
                $rutaCarpetaAlumno = $this->generarRutaCarpeta($alumno);
                $conteoConstancias = DocumentoNivel::where('nivel_id', $this->nivelId)
                    ->where('tipo_doc', 'constancia')
                    ->where('ruta_doc', 'like', $rutaCarpetaAlumno . '%')
                    ->count();

                $alumnosConConteo[] = (object) [
                    'id_alumno' => $alumno->id_alumno,
                    'nombre_completo' => $alumno->nombre_completo,
                    'matricula' => $alumno->matricula,
                    'constancias_count' => $conteoConstancias
                ];
            }
        }

        // Ordenar por nombre completo
        usort($alumnosConConteo, function ($a, $b) {
            return strcmp($a->nombre_completo, $b->nombre_completo);
        });

        return $alumnosConConteo;
    }

    public function getConteoConstanciasNivel()
    {
        return DocumentoNivel::where('nivel_id', $this->nivelId)
            ->where('tipo_doc', 'constancia')
            ->count();
    }

    public function getConteoConstanciasAlumno($alumnoId)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno) return 0;

        $rutaCarpetaAlumno = $this->generarRutaCarpeta($alumno);

        return DocumentoNivel::where('nivel_id', $this->nivelId)
            ->where('tipo_doc', 'constancia')
            ->where('ruta_doc', 'like', $rutaCarpetaAlumno . '%')
            ->count();
    }

    public function getConstanciasAlumno($alumnoId)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno) return collect();

        $rutaCarpetaAlumno = $this->generarRutaCarpeta($alumno);

        return DocumentoNivel::where('nivel_id', $this->nivelId)
            ->where('tipo_doc', 'constancia')
            ->where('ruta_doc', 'like', $rutaCarpetaAlumno . '%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function generarRutaCarpeta($alumno = null)
    {
        $nivel = $this->nivel->nivel;
        $grupo = $this->limpiarNombreArchivo($this->nivel->nombre_grupo);

        // Estructura base: Nivel_1A_Grupo_101
        $rutaBase = "Nivel_{$nivel}_Grupo_{$grupo}/Constancias";

        if ($alumno) {
            // Usar el nombre completo del alumno
            $matricula = $this->limpiarNombreArchivo($alumno->matricula);
            $nombreAlumno = $this->limpiarNombreArchivo($alumno->nombre_completo);
            return "{$rutaBase}/{$matricula}_{$nombreAlumno}";
        }

        return $rutaBase;
    }

    private function limpiarNombreArchivo($nombre)
    {
        // Solo para nombres de grupo, no para nombres de alumnos
        $nombre = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nombre);
        return trim($nombre, '_');
    }

    public function resetForm()
    {
        $this->reset('files');
    }

    public function getNivelInfo()
    {
        if ($this->nivel) {
            // Contar alumnos a través de expedientes
            $totalAlumnos = Expediente::where('nivel_id', $this->nivelId)->count();

            return [
                'profesor' => $this->nivel->profesor->nombre_completo ?? $this->nivel->profesor->nombre,
                'nivel_grupo' => $this->nivel->nivel . ' - ' . $this->nivel->nombre_grupo,
                'modalidad' => $this->nivel->modalidad->tipo_modalidad,
                'aula' => $this->nivel->aula,
                'total_alumnos' => $totalAlumnos
            ];
        }
        return null;
    }
}