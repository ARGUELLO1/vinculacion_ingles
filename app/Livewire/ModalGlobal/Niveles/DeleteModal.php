<?php

namespace App\Livewire\ModalGlobal\Niveles;

use Livewire\Component;
use App\Models\Nivel;

class DeleteModal extends Component
{
    public $isOpen = false;
    public $nivelId;
    public $nivelData;

    protected $listeners = ['openDeleteNivelModal' => 'openModal'];

    public function openModal($nivelId)
    {
        // Si el parámetro viene como array
        if (is_array($nivelId) && isset($nivelId['nivelId'])) {
            $nivelId = $nivelId['nivelId'];
        }

        $nivelId = (int) $nivelId;

        // Buscar el nivel
        $nivel = Nivel::find($nivelId);

        if ($nivel) {
            $this->nivelId = $nivelId;
            $this->nivelData = $nivel->nivel . ' - ' . $nivel->nombre_grupo;
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['nivelId', 'nivelData']);
    }

    public function deleteNivel()
    {
        if ($this->nivelId) {
            try {
                $nivel = Nivel::find($this->nivelId);

                if ($nivel) {
                    $nivelData = $nivel->nivel . ' - ' . $nivel->nombre_grupo;
                    $nivel->delete();

                    // Emitir evento de éxito
                    $this->closeModal();
                    session()->flash('success', 'Nivel ' . $nivelData . ' eliminado correctamente');
                    return $this->redirectRoute('capturista.niveles.index', navigate: true);
                }
            } catch (\Exception $e) {
                // Emitir evento de error
                $this->dispatch('showErrorMessage', message: "Error al eliminar el nivel: " . $e->getMessage());
            }
        }

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modal-global.niveles.delete-modal');
    }
}
