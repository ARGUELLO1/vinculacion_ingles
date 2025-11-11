<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use App\Models\Expediente;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Principal extends Component
{
    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;
    public $nota;
    public $documentos;


    public function mount()
    {
        $this->info_alumno = Auth::user()->alumno;

        if ($this->info_alumno->nivel_id) {
            $this->nota = Nota::where('nivel_id', $this->info_alumno->nivel->id_nivel)->where('alumno_id', $this->info_alumno->id_alumno)->first();
            $this->documentos = Expediente::where('alumno_id', $this->info_alumno->id_alumno)->where('nivel_id', $this->info_alumno->nivel_id)->first();
            //dd($this->documentos->documentosExpedientes);
        }
    }

    public function render()
    {
        return view('livewire.alumno.principal');
    }
}
