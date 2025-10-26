<?php

namespace App\Livewire\Admin\Usuarios\Profesores;

use App\Livewire\Forms\Admin\Usuarios\ProfesoresForm;
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
        return view('livewire.admin.usuarios.profesores.create');
    }
}
