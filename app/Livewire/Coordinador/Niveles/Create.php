<?php

namespace App\Livewire\Coordinador\Niveles;

use App\Livewire\Forms\Niveles\NivelesForm;
use App\Models\Modalidad;
use App\Models\Periodo;
use App\Models\Profesor;
use Livewire\Component;

class Create extends Component
{
    public NivelesForm $form;

    public function save(): void
    {
        $this->form->store();

        session()->flash('success', 'Nivel ' . $this->form->nivelName->nivel . ' - ' . $this->form->nivelName->nombre_grupo . ' creado correctamente');

        $this->redirectRoute('coordinador.niveles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.coordinador.niveles.create', [
            'profesores' => Profesor::all(),
            'periodos' => Periodo::all(),
            'modalidades' => Modalidad::all()
        ]);
    }
}