<?php

namespace App\Livewire\Capturista\Niveles;

use App\Models\Nivel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searh = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $niveles = Nivel::with([
            'profesor',
            'periodo',
            'modalidad'
        ])
            ->when($this->searh, function ($query) {
                $query->where(function ($q) {
                    $q->where('nivel', 'like', '%' . $this->searh . '%')
                        ->orWhere('nombre_grupo', 'like', '%' . $this->searh . '%')
                        ->orWhereHas('profesor', function ($subQuery) {
                            $subQuery->where('nombre', 'like', '%' . $this->searh . '%')
                                ->orWhere('ap_paterno', 'like', '%' . $this->searh . '%')
                                ->orWhere('ap_materno', 'like', '%' . $this->searh . '%')
                                ->orWhereRaw("CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) LIKE ?", ['%' . $this->search . '%']);
                        })
                        ->orWhereHas('periodo', function ($subQuery) {
                            $subQuery->where('periodo', 'like', '%' . $this->searh . '%');
                        })
                        ->orWhereHas('modalidad', function ($subQuery) {
                            $subQuery->where('tipo_modalidad', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->paginate($this->perPage);

        return view('livewire.capturista.niveles.index', [
            'niveles' => $niveles
        ]);
    }
}