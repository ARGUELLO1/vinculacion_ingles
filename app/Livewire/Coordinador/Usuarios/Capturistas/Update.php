<?php

namespace App\Livewire\Coordinador\Usuarios\Capturistas;

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

        $this->redirectRoute('coordinador.capturistas.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.coordinador.usuarios.capturistas.create');
    }
}
