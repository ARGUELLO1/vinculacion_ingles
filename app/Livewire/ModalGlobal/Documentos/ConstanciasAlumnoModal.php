<?php

namespace App\Livewire\ModalGlobal\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ConstanciasForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConstanciasAlumnoModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $nivelId;
    public $conteoConstanciasNivel = 0;
    public $nivelInfo = [];

    public ConstanciasForm $form;

    protected $listeners = [
        'abrirModalEliminarConstancias' => 'abrirModal',
        'cerrarModalEliminarConstancias' => 'cerrarModal'
    ];

    public function abrirModal($nivelId, $conteoConstanciasNivel = 0, $nivelInfo = [])
    {
        $this->nivelId = $nivelId;
        $this->conteoConstanciasNivel = $conteoConstanciasNivel;
        $this->nivelInfo = $nivelInfo;
        $this->form->setNivel($nivelId);
        $this->isOpen = true;
    }

    public function cerrarModal()
    {
        $this->isOpen = false;
        $this->reset(['nivelId', 'conteoConstanciasNivel', 'nivelInfo']);
    }

    public function eliminarTodasConstanciasNivel()
    {
        $result = $this->form->eliminarTodasConstanciasNivel();

        if ($result['success']) {
            $this->dispatch('constanciasEliminadas');
            $this->cerrarModal();
            $this->dispatch('show-message', $result['message'], 'success');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function render()
    {
        return view('livewire.modal-global.documentos.constancias-alumno-modal');
    }
}
