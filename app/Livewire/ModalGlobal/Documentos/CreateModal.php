<?php

namespace App\Livewire\ModalGlobal\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ProfesoresForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $nivelId;

    public ProfesoresForm $form;

    protected $listeners = [
        'abrirModalSubir' => 'abrirModal',
        'cerrarModal' => 'cerrarModal'
    ];

    public function abrirModal($nivelId)
    {
        $this->nivelId = $nivelId;
        $this->form->setNivel($nivelId);
        $this->isOpen = true;
    }

    public function cerrarModal()
    {
        $this->isOpen = false;
        $this->reset(['nivelId']);
        $this->form->resetForm();
    }

    public function subirPlaneacion()
    {
        $result = $this->form->subirPlaneacion();

        if ($result['success']) {
            $this->dispatch('documentoSubido');
            $this->cerrarModal();
            $this->dispatch('show-message', $result['message'], 'success');

            $this->redirectRoute('admin.documentos.profesor', navigate: true);
        } else {
            $this->dispatch('show-message', $result['message'], 'error');

            $this->redirectRoute('admin.documentos.profesor', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.modal-global.documentos.create-modal');
    }
}
