<?php

namespace App\Livewire\Coordinador\Usuarios\Profesores;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $profesores = User::role('profesor')
            ->with('profesor')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('profesor', function ($subQuery) {
                            $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                                ->orWhere('ap_paterno', 'like', '%' . $this->search . '%')
                                ->orWhere('ap_materno', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->paginate($this->perPage);

        return view('livewire.coordinador.usuarios.profesores.index', [
            'profesores' => $profesores
        ]);
    }
}
