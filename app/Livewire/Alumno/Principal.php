<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Principal extends Component
{
    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;
    public $info_grupo;

    
    public function mount()
    {
        $this->info_alumno=Auth::user()->alumno;
        
        $this->info_grupo=Alumno::find($this->info_alumno->id_alumno);

    }

    public function render()
    {
        return view('livewire.alumno.principal');
    }
}
