<?php

namespace App\Livewire\ModalGlobal\AdminCoordinador;

use Livewire\Component;

class OptionsModal extends Component
{
    public $showModal = false;
    public $selectedNivel = null;

    protected $listeners = [
        'openActionsModal' => 'openModal'
    ];

    public function openModal($nivelId)
    {
        $this->selectedNivel = $nivelId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedNivel = null;
        $this->dispatch('closeActionsModal');
    }

    public function generarActa()
    {
        $this->dispatch('generarActa', nivelId: $this->selectedNivel);
        $this->closeModal();
    }

    public function generarConstancia()
    {
        $this->dispatch('generarConstancia', nivelId: $this->selectedNivel);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modal-global.admin-coordinador.options-modal');
    }
}
