<?php

namespace App\Livewire\Coordinador\Usuarios\Profesores;

use App\Livewire\Forms\AdminCoordinador\Usuarios\ProfesoresForm;
use App\Models\Profesor;
use Livewire\Component;

class Update extends Component
{
    public ProfesoresForm $form;

    public function mount(Profesor $profesor)
    {
        $profesor->load('user');
        $this->form->setProfesor($profesor);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Profesor con id ' . $this->form->profesorID->id_profesor . ' actualizado correctamente');

        $this->redirectRoute('admin.profesores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.coordinador.usuarios.profesores.create');
    }
}