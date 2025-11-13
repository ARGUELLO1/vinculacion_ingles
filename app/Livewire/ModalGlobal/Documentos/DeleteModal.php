<?php

namespace App\Livewire\ModalGlobal\Documentos;

use App\Livewire\Forms\AdminCoordinador\Documentos\ProfesoresForm;
use Livewire\Component;

class DeleteModal extends Component
{
    public $isOpen = false;
    public $documentoId;
    public $documento;

    public ProfesoresForm $form;

    protected $listeners = ['openDeleteDocumentoModal' => 'loadDocumento'];

    public function loadDocumento($documentoId)
    {
        $this->documentoId = $documentoId;
        $result = $this->form->obtenerInfoDocumento($documentoId);

        if ($result['success']) {
            $this->documento = $result['documento'];
            $this->isOpen = true;
        } else {
            $this->dispatch('show-message', $result['message'], 'error');
        }
    }

    public function deleteDocumento()
    {
        $result = $this->form->eliminarPlaneacion($this->documentoId);

        if ($result['success']) {
            $this->isOpen = false;
            $this->dispatch('documentoEliminado');
            $this->dispatch('show-message', $result['message'], 'success');

            $this->redirectRoute('admin.documentos.profesor');
        } else {
            $this->dispatch('show-message', $result['message'], 'error');

            $this->redirectRoute('admin.documentos.profesor');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['documentoId', 'documento']);
        $this->form->resetForm();
    }

    public function render()
    {
        return view('livewire.modal-global.documentos.delete-modal');
    }
}
