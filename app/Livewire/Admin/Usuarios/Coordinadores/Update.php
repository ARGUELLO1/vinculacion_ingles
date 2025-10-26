<?php

namespace App\Livewire\Admin\Usuarios\Coordinadores;

use App\Livewire\Forms\Admin\Usuarios\CoordinadoresForm;
use App\Models\Coordinador;
use Livewire\Component;

class Update extends Component
{
    public CoordinadoresForm $form;

    public function mount(Coordinador $coordinador)
    {
        $coordinador->load('user');
        $this->form->setCoordinador($coordinador);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Coordinador con id ' . $this->form->coordinadorID->id_coordinador . ' actualizado correctamente');

        $this->redirectRoute('admin.coordinadores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.usuarios.coordinadores.create');
    }
}
