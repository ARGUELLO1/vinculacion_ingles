<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Principal extends Component
{
    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;
    public $nota;
    public $documentos;
    public $existe_constancia;
    public $constancia;


    public function mount()
    {
        $this->info_alumno = Auth::user()->alumno;
        if ($this->info_alumno->nivel_id) {
            $this->nota = Nota::where('nivel_id', $this->info_alumno->nivel->id_nivel)->where('alumno_id', $this->info_alumno->id_alumno)->first();
            $this->documentos = Expediente::where('alumno_id', $this->info_alumno->id_alumno)->where('nivel_id', $this->info_alumno->nivel_id)->first();
            //Buscamos la constancia del alumno
            if ($this->info_alumno->nivel->documentosNiveles) {
                $carpeta=dirname($this->info_alumno->nivel->documentosNiveles->ruta_doc);
                $matricula=$this->info_alumno->matricula;
                $this->constancia = Storage::disk('expedientesNiveles')->allDirectories($carpeta);
                $this->constancia=collect($this->constancia)->first(function ($carpeta) use ($matricula) {
                    return Str::startsWith(basename($carpeta), $matricula);
                });
                $this->constancia =  Storage::disk('expedientesNiveles')->files($this->constancia);
            }
        }
    }

    public function render()
    {
        return view('livewire.alumno.principal');
    }
}
