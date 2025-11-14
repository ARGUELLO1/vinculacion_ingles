<?php

namespace App\Livewire\Admin\Documentos;

use App\Models\Nivel;
use Livewire\Component;
use Livewire\WithPagination;

class Profesor extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function render()
    {
        $niveles = Nivel::with([
            'profesor',
            'modalidad',
            'documentosProfesores' => function ($query) {
                $query->where('tipo_doc', 'planeacion');
            }
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nivel', 'like', '%' . $this->search . '%')
                        ->orWhere('nombre_grupo', 'like', '%' . $this->search . '%')
                        ->orWhereHas('profesor', function ($subQuery) {
                            $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                                ->orWhere('ap_paterno', 'like', '%' . $this->search . '%')
                                ->orWhere('ap_materno', 'like', '%' . $this->search . '%')
                                ->orWhereRaw("CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) LIKE ?", ['%' . $this->search . '%']);
                        });
                });
            })
            ->where('nivel_concluido', '0') // Solo niveles activos
            ->orderBy('nivel', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.documentos.profesor', [
            'niveles' => $niveles
        ]);
    }

    public function abrirModalSubir($nivelId)
    {
        $this->dispatch('abrirModalSubir', nivelId: $nivelId);
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'perPage'])) {
            $this->resetPage();
        }
    }

    // Escuchar eventos
    protected $listeners = [
        'documentoEliminado' => 'onDocumentoEliminado',
        'documentoSubido' => 'onDocumentoSubido',
        'show-message' => 'showMessage'
    ];

    public function onDocumentoEliminado()
    {
        // Actualizar la vista cuando se elimina un documento
    }

    public function onDocumentoSubido()
    {
        // Actualizar la vista cuando se sube un documento
    }

    public function showMessage($message, $type = 'success')
    {
        session()->flash($type === 'error' ? 'error' : 'message', $message);
    }
}
