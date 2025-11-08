<?php

namespace App\Livewire\Alumno;

use App\Models\DocumentoNivel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Carterm extends Component
{
    //Datos del alumno
    public $datos_alumno;
    public $carta_documento;
    public $archivos;

    public function mount()
    {
        $this->datos_alumno = Auth::user()->alumno;
        if ($this->datos_alumno->nivel_id) {
            //Sacar los datos de los documentos para ver si hay documentos o todavía no
            $this->carta_documento = DocumentoNivel::where('nivel_id', $this->datos_alumno->nivel->id_nivel)->first();

            //Ruta de la carpeta en donde estan las cartas de termino
            if ($this->carta_documento) {
                //obtenemos el nombre de todos los archivos que existen en esa carpeta
                $this->archivos = Storage::files($this->carta_documento->ruta_doc);
            }
        } 
    }


    public function render()
    {
        return view('livewire.alumno.carterm');
    }
}
