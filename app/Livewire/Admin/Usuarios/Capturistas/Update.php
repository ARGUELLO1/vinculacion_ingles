<?php

namespace App\Livewire\Admin\Usuarios\Capturistas;

use App\Livewire\Forms\AdminCoordinador\Usuarios\CapturistasForm;
use App\Models\Capturista;
use Livewire\Component;

class Update extends Component
{
    public CapturistasForm $form;

    public function mount(Capturista $capturista)
    {
        $capturista->load('user');
        $this->form->setCapturista($capturista);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Capturista con id ' . $this->form->capturistaID->id_capturista . ' actualizado correctamente');

        $this->redirectRoute('admin.capturistas.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.usuarios.capturistas.create');
    }
}
