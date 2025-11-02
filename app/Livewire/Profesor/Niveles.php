<?php

namespace App\Livewire\Profesor;

use App\Models\Nivel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Niveles extends Component
{
    public $nivelSeleccionado = null;
    public $grupos = [];

    public function render()
    {
        return view('livewire.profesor.niveles');
    }

    public function verNivel($idNivel)
    {
        $this->nivelSeleccionado = $idNivel;

        $profesorId = Auth::user()->profesor->id_profesor ?? null;

        $this->grupos = Nivel::where('id_nivel', $idNivel)
            ->where('profesor_id', $profesorId)
            ->get();
    }

    public function regresar()
    {
        $this->nivelSeleccionado = null;
        $this->grupos = [];
    }
}
