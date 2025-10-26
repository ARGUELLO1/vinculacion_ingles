<?php

namespace App\Livewire\Admin\Usuarios\Coordinadores;

use App\Livewire\Forms\Admin\Usuarios\CoordinadoresForm;
use Livewire\Component;

class Create extends Component
{
    public CoordinadoresForm $form;

    public function save(): void
    {
        $this->form->store();

        session()->flash('success', 'Coordinador ' . $this->form->coordinadorName->nombre . ' creado correctamente');

        $this->redirectRoute('admin.coordinadores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.usuarios.coordinadores.create');
    }
}
