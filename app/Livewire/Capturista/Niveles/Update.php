<?php

namespace App\Livewire\Capturista\Niveles;

use App\Livewire\Forms\Niveles\NivelesForm;
use App\Models\Modalidad;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\Profesor;
use Livewire\Component;

class Update extends Component
{
    public NivelesForm $form;

    public function mount(Nivel $nivel)
    {
        $nivel->load([
            'profesor',
            'periodo',
            'modalidad'
        ]);
        $this->form->setNivel($nivel);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('success', 'Nivel ' . $this->form->nivelGrupo->nivel . ' - ' . $this->form->nivelGrupo->nombre_grupo . ' actualizado correctamente');

        $this->redirectRoute('capturista.niveles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.capturista.niveles.create', [
            'profesores' => Profesor::all(),
            'periodos' => Periodo::all(),
            'modalidades' => Modalidad::all()
        ]);
    }
}