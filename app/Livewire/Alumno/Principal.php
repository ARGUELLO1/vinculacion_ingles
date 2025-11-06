<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Principal extends Component
{
    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;
    public $info_grupo = [];
    public $nota;


    public function mount()
    {
        $this->info_alumno = Auth::user()->alumno;
        $this->nota=Nota::where('nivel_id',$this->info_alumno->nivel->id_nivel)->where('alumno_id',$this->info_alumno->id_alumno)->first();
  
    }

    public function render()
    {
        return view('livewire.alumno.principal');
    }
}
