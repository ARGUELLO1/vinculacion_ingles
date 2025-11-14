<?php

namespace App\Livewire\ModalGlobal\CalificaionesAlumnos;

use App\Models\Alumno;
use App\Models\Nota;
use Livewire\Component;

class ShowModal extends Component
{
    public $isOpen = false;
    public $alumnoId;
    public $nivelId;
    public $alumno;
    public $nota;
    public $nivelGrupo;
    public $nombreAlumno;

    protected $listeners = ['abrirModalCalificaciones'];

    public function abrirModalCalificaciones($alumnoId, $nivelId = null)
    {
        $this->alumnoId = $alumnoId;
        $this->nivelId = $nivelId;
        $this->cargarDatosAlumno();
        $this->isOpen = true;
    }

    public function cargarDatosAlumno()
    {
        // Cargar datos del alumno
        $this->alumno = Alumno::with(['nivel', 'nota'])->find($this->alumnoId);

        if ($this->alumno) {
            $this->nombreAlumno = $this->alumno->nombre_completo;
            $this->nivelGrupo = $this->alumno->nivel ?
                $this->alumno->nivel->nivel . ' - ' . $this->alumno->nivel->nombre_grupo :
                'Nivel no asignado';

            // Cargar la nota del alumno para el nivel específico
            if ($this->nivelId) {
                $this->nota = Nota::where('alumno_id', $this->alumnoId)
                    ->where('nivel_id', $this->nivelId)
                    ->first();
            } else {
                $this->nota = $this->alumno->nota->first();
            }
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['alumnoId', 'nivelId', 'alumno', 'nota', 'nivelGrupo', 'nombreAlumno']);
    }

    public function render()
    {
        return view('livewire.modal-global.calificaiones-alumnos.show-modal');
    }
}
