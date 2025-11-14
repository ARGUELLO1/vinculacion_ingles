<?php

namespace App\Livewire\ModalGlobal\AdminCoordinador;

use Livewire\Component;

class ReportesModal extends Component
{
    public $showModal = false;
    public $selectedNivel = null;

    protected $listeners = [
        'openDownloadModal' => 'openModal'
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
        $this->dispatch('closeDownloadModal');
    }

    public function generarPDF()
    {
        $this->dispatch('generarPDF', nivelId: $this->selectedNivel);
        $this->closeModal();
    }

    public function generarExcel()
    {
        $this->dispatch('generarExcel', nivelId: $this->selectedNivel);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modal-global.admin-coordinador.reportes-modal');
    }
}
