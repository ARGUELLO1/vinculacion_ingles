<?php

namespace App\Livewire\Admin\Usuarios\Capturistas;

use App\Livewire\Forms\AdminCoordinador\Usuarios\CapturistasForm;
use Livewire\Component;

class Create extends Component
{
    public CapturistasForm $form;

    public function save(): void
    {
        $this->form->store();

        session()->flash('success', 'Capturista ' . $this->form->capturistaName->nombre . ' creado correctamente');

        $this->redirectRoute('admin.capturistas.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.usuarios.capturistas.create');
    }
}
