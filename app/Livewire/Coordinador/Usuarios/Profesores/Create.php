<?php

namespace App\Livewire\Coordinador\Usuarios\Profesores;

use App\Livewire\Forms\Coordinador\Usuarios\ProfesoresForm;
use Livewire\Component;

class Create extends Component
{
    public ProfesoresForm $form;

    public function save(): void
    {
        $this->form->store();

        session()->flash('success', 'Profesor ' . $this->form->profesorName->nombre . ' creado correctamente');

        $this->redirectRoute('admin.profesores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.coordinador.usuarios.profesores.create');
    }
}
