<?php

namespace App\Livewire\Admin\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ConstanciasForm;
use App\Models\Alumno;
use Livewire\Component;

class Constacias extends Component
{
    public $nivelId;
    public $detalleAlumno = null;

    public ConstanciasForm $form;

    public function mount($nivelId)
    {
        $this->nivelId = $nivelId;
        $this->form->setNivel($nivelId);
    }

    public function render()
    {
        $alumnos = $this->form->getAlumnosConConteo();

        $constanciasAlumno = null;
        if ($this->detalleAlumno) {
            $constanciasAlumno = $this->form->getConstanciasAlumno($this->detalleAlumno);
        }

        return view('livewire.admin.documentos.constacias', [
            'alumnos' => $alumnos,
            'conteoConstanciasNivel' => $this->form->getConteoConstanciasNivel(),
            'nivelInfo' => $this->form->getNivelInfo(),
            'constanciasAlumno' => $constanciasAlumno,
            'alumnoDetalle' => $this->detalleAlumno ? Alumno::find($this->detalleAlumno) : null
        ]);
    }

    public function abrirModalSubir($alumnoId = null)
    {
        $this->dispatch(
            'abrirModalConstancias',
            nivelId: $this->nivelId,
            alumnoId: $alumnoId,
            nivelInfo: $this->form->getNivelInfo()
        );
    }

    public function abrirModalEliminarTodoNivel()
    {
        $this->dispatch(
            'abrirModalEliminarConstancias',
            nivelId: $this->nivelId,
            conteoConstanciasNivel: $this->form->getConteoConstanciasNivel(),
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

    public function eliminarTodasConstanciasAlumno($alumnoId)
    {
        $result = $this->form->eliminarTodasConstanciasAlumno($alumnoId);

        if ($result['success']) {
            $this->detalleAlumno = null;
            $this->dispatch('show-message', $result['message'], 'success');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function verConstanciasAlumno($alumnoId)
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
        'constanciasEliminadas' => 'onConstanciasEliminadas',
        'eliminarConstanciasAlumnoConfirmado' => 'eliminarTodasConstanciasAlumno'
    ];

    public function onDocumentosSubidos()
    {
        // Actualizar la vista cuando se suben documentos
    }

    public function onConstanciasEliminadas()
    {
        // Actualizar la vista cuando se eliminan constancias
        $this->detalleAlumno = null;
    }

    public function showMessage($message, $type = 'success')
    {
        session()->flash($type === 'error' ? 'error' : 'message', $message);
    }
}