<?php

namespace App\Livewire\ModalGlobal\Asistencias;

use App\Models\Asistencia;
use App\Models\Nivel;
use App\Models\Alumno;
use Livewire\Component;

class UpdateModal extends Component
{
    public $isOpen = false;
    public $nombreAlumno;
    public $nivelGrupo;
    public $fechaSeleccionada;
    public $asistenciaSeleccionada;
    public $alumnoId;
    public $nivelId;

    public $todasAsistencias = [];
    public $fechasDisponibles = [];
    public $asistenciaDelDia = null;
    public $parcialActivo = 1;
    public $nivelData;

    protected $listeners = ['openUpdateAsistenciaModal' => 'openModal'];

    public function openModal($alumnoId = null, $nivelId = null)
    {
        // Manejar parámetros de diferentes formas
        if (is_array($alumnoId)) {
            $this->alumnoId = $alumnoId['alumnoId'] ?? null;
            $this->nivelId = $alumnoId['nivelId'] ?? null;
        } else {
            $this->alumnoId = $alumnoId;
            $this->nivelId = $nivelId;
        }

        // Si no tenemos parámetros, intentar obtenerlos de la sesión o mostrar error
        if (!$this->alumnoId) {
            $this->isOpen = false;
            session()->flash('error', 'No se pudo identificar al alumno.');
            return;
        }

        $this->loadData();
        $this->isOpen = true;
    }

    private function loadData()
    {
        try {
            // Cargar datos del nivel para obtener el parcial activo
            if ($this->nivelId) {
                $this->nivelData = Nivel::find($this->nivelId);
                if ($this->nivelData) {
                    $this->parcialActivo = $this->nivelData->parcial_activo;
                    $this->nivelGrupo = $this->nivelData->nivel . ' - ' . $this->nivelData->nombre_grupo;
                }
            }

            // Cargar nombre del alumno
            $alumno = Alumno::find($this->alumnoId);
            if ($alumno) {
                $this->nombreAlumno = $alumno->nombre_completo ?? $alumno->nombre . ' ' . $alumno->ap_paterno . ' ' . $alumno->ap_materno;
            }

            // Cargar asistencias
            $this->loadAsistencias();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar los datos: ' . $e->getMessage());
        }
    }

    private function loadAsistencias()
    {
        $query = Asistencia::with(['alumno', 'nivel'])
            ->where('alumno_id', $this->alumnoId);

        if ($this->nivelId) {
            $query->where('nivel_id', $this->nivelId)
                ->where('parcial', $this->parcialActivo);
        }

        $this->todasAsistencias = $query->orderBy('fecha', 'desc')->get();
        $this->fechasDisponibles = $this->todasAsistencias->pluck('fecha')->unique()->sortDesc()->values();

        $this->fechaSeleccionada = null;
        $this->asistenciaSeleccionada = null;
        $this->asistenciaDelDia = null;
    }

    // ... el resto de los métodos se mantienen igual ...
    public function updatedFechaSeleccionada()
    {
        $this->asistenciaSeleccionada = null;
        $this->cargarAsistenciaDelDia();
    }

    private function cargarAsistenciaDelDia()
    {
        if ($this->fechaSeleccionada) {
            $asistencia = $this->todasAsistencias->firstWhere('fecha', $this->fechaSeleccionada);
            if ($asistencia) {
                $this->asistenciaSeleccionada = $asistencia->asistencia;
                $this->asistenciaDelDia = $asistencia;
            } else {
                $this->asistenciaSeleccionada = null;
                $this->asistenciaDelDia = null;
            }
        }
    }

    public function guardarAsistencia()
    {
        $this->validate([
            'fechaSeleccionada' => 'required',
            'asistenciaSeleccionada' => 'required',
        ]);

        if (!$this->nivelData) {
            session()->flash('error', 'No se pudo verificar el nivel.');
            return;
        }

        $campoParcial = 'parcial_' . $this->parcialActivo;
        if ($this->nivelData->$campoParcial != '1') {
            session()->flash('error', 'No puedes modificar asistencias de un parcial inactivo.');
            return;
        }

        if ($this->asistenciaDelDia) {
            $this->asistenciaDelDia->update([
                'asistencia' => $this->asistenciaSeleccionada
            ]);

            session()->flash('success', 'Asistencia actualizada correctamente para el Parcial ' . $this->parcialActivo);
            $this->dispatch('asistenciaActualizada');
            $this->closeModal();
        } else {
            session()->flash('error', 'No se encontró la asistencia para modificar.');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetExcept('isOpen');
    }

    public function render()
    {
        return view('livewire.modal-global.asistencias.update-modal');
    }
}
