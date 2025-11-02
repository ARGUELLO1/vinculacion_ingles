<?php

namespace App\Livewire\Coordinador\Permisos;

use App\Models\User;
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
        $capturistas = User::role('capturista')
            ->with([
                'capturista',
                'roles',
                'permissions'
            ])
            ->when($this->searh, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searh . '%')
                        ->orWhere('email', 'like', '%' . $this->searh . '%')
                        ->orWhere('capturista', function ($subQuery) {
                            $subQuery->where('nombre', 'like', '%' . $this->searh . '%')
                                ->orWhere('ap_paterno', 'like', '%' . $this->searh . '%')
                                ->orWhere('ap_materno', 'like', '%' . $this->searh . '%');
                        });
                });
            })
            ->paginate($this->perPage);

        return view('livewire.coordinador.perimsos.index', [
            'capturistas' => $capturistas
        ]);
    }
}