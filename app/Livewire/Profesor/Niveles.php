<?php

namespace App\Livewire\Profesor;

use App\Models\Nivel;
use App\Models\Periodo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // 1. IMPORTAMOS DB PARA EL CONTEO
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Niveles extends Component
{
    public $nivelSeleccionado = null;
    public Collection|array $grupos = [];
    public $alumnosCount = 0;

    // 2. NUEVA PROPIEDAD PARA GUARDAR LOS NIVELES DEL PROFESOR
    public Collection|array $nivelesDisponibles = [];

    /**
     * Metodo solo para usar si hay componentes que se deben cargan una vez xd
     */
    public function mount() {

    }

    public function render()
    {
        $profesorId = Auth::user()->profesor->id_profesor ?? null;

        $this->nivelesDisponibles = Nivel::where('profesor_id', $profesorId)
            ->select('nivel', DB::raw('count(*) as group_count'))
            ->groupBy('nivel')
            ->orderBy('nivel', 'asc')
            ->get();
        return view('livewire.profesor.niveles');
    }

    /**
     * Carga los grupos y las estadísticas para el nivel seleccionado.
     */
    public function verNivel($nivelNum)
    {
        $this->nivelSeleccionado = $nivelNum;
        $profesorId = Auth::user()->profesor->id_profesor ?? null;

        $this->grupos = Nivel::where('profesor_id', $profesorId)
            ->where('nivel', $nivelNum)
            ->withCount('alumnos')
            ->with(['periodo'])
            ->orderBy('nombre_grupo')
            ->get();

        $this->alumnosCount = $this->grupos->sum('alumnos_count');
    }

    /**
     * Resetea la vista para volver a la selección de Nivel.
     */
    public function regresar()
    {
        $this->nivelSeleccionado = null;
        $this->grupos = [];
        $this->alumnosCount = 0;
    }
}
