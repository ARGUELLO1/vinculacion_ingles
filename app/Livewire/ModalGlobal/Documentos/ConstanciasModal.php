<?php

namespace App\Livewire\ModalGlobal\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ConstanciasForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConstanciasModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $nivelId;
    public $alumnoSeleccionado = null;
    public $nivelInfo = [];

    public ConstanciasForm $form;

    protected $listeners = [
        'abrirModalConstancias' => 'abrirModal',
        'cerrarModalConstancias' => 'cerrarModal'
    ];

    public function abrirModal($nivelId, $alumnoId = null, $nivelInfo = [])
    {
        $this->nivelId = $nivelId;
        $this->alumnoSeleccionado = $alumnoId;
        $this->nivelInfo = $nivelInfo;
        $this->form->setNivel($nivelId);
        $this->isOpen = true;
    }

    public function cerrarModal()
    {
        $this->isOpen = false;
        $this->reset(['nivelId', 'alumnoSeleccionado', 'nivelInfo']);
        $this->form->resetForm();
    }

    public function subirConstancias()
    {
        $result = $this->form->subirConstancias($this->alumnoSeleccionado);

        if ($result['success']) {
            $this->dispatch('documentosSubidos');
            $this->cerrarModal();
            $this->dispatch('show-message', $result['message'], 'success');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function render()
    {
        return view('livewire.modal-global.documentos.constancias-modal');
    }
}