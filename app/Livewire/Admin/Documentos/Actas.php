<?php

namespace App\Livewire\Admin\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ActasForm;
use App\Models\Alumno;
use Livewire\Component;

class Actas extends Component
{
    public $nivelId;
    public $detalleAlumno = null;

    public ActasForm $form;

    public function mount($nivelId)
    {
        $this->nivelId = $nivelId;
        $this->form->setNivel($nivelId);
    }

    public function render()
    {
        $alumnos = $this->form->getAlumnosConConteo();

        $actasAlumno = null;
        if ($this->detalleAlumno) {
            $actasAlumno = $this->form->getActasAlumno($this->detalleAlumno);
        }

        return view('livewire.admin.documentos.actas', [
            'alumnos' => $alumnos,
            'conteoActasNivel' => $this->form->getConteoActasNivel(),
            'nivelInfo' => $this->form->getNivelInfo(),
            'actasAlumno' => $actasAlumno,
            'alumnoDetalle' => $this->detalleAlumno ? Alumno::find($this->detalleAlumno) : null
        ]);
    }

    public function abrirModalSubir($alumnoId = null)
    {
        $this->dispatch(
            'abrirModalActas',
            nivelId: $this->nivelId,
            alumnoId: $alumnoId,
            nivelInfo: $this->form->getNivelInfo()
        );
    }

    public function abrirModalEliminarTodoNivel()
    {
        $this->dispatch(
            'abrirModalEliminarActas',
            nivelId: $this->nivelId,
            conteoactasNivel: $this->form->getConteoActasNivel(),
            nivelInfo: $this->form->getNivelInfo()
        );
    }

    public function abrirModalEliminarAlumno($alumnoId)
    {
        $this->dispatch('abrirModalEliminarAlumno', $alumnoId);
    }

    public function eliminarConstancia($documentoId)
    {
        $result = $this->form->eliminarConstancia($documentoId);

        if ($result['success']) {
            $this->dispatch('show-message', $result['message'], 'success');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function eliminarTodasactasAlumno($alumnoId)
    {
        $result = $this->form->eliminarTodasActasAlumno($alumnoId);

        if ($result['success']) {
            $this->detalleAlumno = null;
            $this->dispatch('show-message', $result['message'], 'success');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function verActasAlumno($alumnoId)
    {
        $this->detalleAlumno = $alumnoId;
    }

    public function cerrarDetalleAlumno()
    {
        $this->detalleAlumno = null;
    }

    protected $listeners = [
        'show-message' => 'showMessage',
        'documentosSubidos' => 'onDocumentosSubidos',
        'actasEliminadas' => 'onactasEliminadas',
        'eliminaractasAlumnoConfirmado' => 'eliminarTodasactasAlumno'
    ];

    public function onDocumentosSubidos()
    {
        // Actualizar la vista cuando se suben documentos
    }

    public function onactasEliminadas()
    {
        // Actualizar la vista cuando se eliminan constancias
        $this->detalleAlumno = null;
    }

    public function showMessage($message, $type = 'success')
    {
        session()->flash($type === 'error' ? 'error' : 'message', $message);
    }
}