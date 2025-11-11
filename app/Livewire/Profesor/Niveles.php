<?php

namespace App\Livewire\Profesor;

use App\Models\Nivel;
use App\Models\Periodo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Niveles extends Component
{
    public $nivelSeleccionado = null;
    public Collection|array $grupos = [];
    public $alumnosCount = 0;
    public Collection|array $nivelesDisponibles = [];
    
    public string $vista = 'activos';
   

   
    public function mount()
    {
     
    }


    public function setVista(string $vista)
    {
        $this->vista = $vista;
       
        $this->regresar(); 
    }
    

 
    public function render()
    {
     
        if (!$this->nivelSeleccionado) {
            $profesorId = Auth::user()->profesor->id_profesor ?? null;


            $this->nivelesDisponibles = Nivel::where('profesor_id', $profesorId)
                ->when($this->vista === 'activos', function ($query) {
                    $query->activos(); 
                })
                ->when($this->vista === 'concluidos', function ($query) {
                    $query->concluidos(); 
                })
                ->select('nivel', DB::raw('count(*) as grupos_count'))
                ->groupBy('nivel')
                ->orderBy('nivel', 'asc')
                ->get();
         
        }

        return view('livewire.profesor.niveles', [
         
            'nivelesDisponibles' => $this->nivelesDisponibles,
        ]);
    }

 
    public function verNivel($nivelNum)
    {
        $this->nivelSeleccionado = $nivelNum;
        $profesorId = Auth::user()->profesor->id_profesor ?? null;


        $this->grupos = Nivel::where('profesor_id', $profesorId)
            ->where('nivel', $nivelNum)
            ->when($this->vista === 'activos', function ($query) {
                $query->activos();
            })
            ->when($this->vista === 'concluidos', function ($query) {
                $query->concluidos();
            })
            ->withCount('alumnos')
            ->with(['periodo'])
            ->orderBy('nombre_grupo')
            ->get();
      

        $this->alumnosCount = $this->grupos->sum('alumnos_count');
    }


    public function regresar()
    {
        $this->nivelSeleccionado = null;
        $this->grupos = [];
        $this->alumnosCount = 0;
    }
}