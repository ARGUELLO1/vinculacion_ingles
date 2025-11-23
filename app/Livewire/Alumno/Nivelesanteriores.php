<?php

namespace App\Livewire\Alumno;

use App\Models\DocumentoExpediente;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
use App\Models\Nivel;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class NivelesAnteriores extends Component
{
    //Información del alumno
    public $info_alumno;
    public $expediente;
    public $niveles;
    public $documentos_nivel;
    public $tabla=false;

    //MODAL
    public $open = false;


    public function mount()
    {
        //Información del alumno
        $this->info_alumno = Auth::user()->alumno;
        $this->info_expediente();
    }


    //FUNCIÓN PARA ABRIR EL MODAL
    public function ver($id_expediente)
    {
        $this->open = true;
        $this->documentos_nivel = DocumentoExpediente::where('id_expediente', $id_expediente)->get();
        $this->info_expediente();
    }

    public function close()
    {
        $this->open = false;
        $this->info_expediente();
    }

    //SE CREÓ ESTA FUNCIÓN PARA VOLVER A CARGAR LOS EXPEDIENTES (YA QUE SE BORRAN AL VOLVER A RENDERIZAR)
    public function info_expediente()
    {

        //Información de expedientes, se sacan todos los expedientes ya que corresponde a los niveles cursados por el alumno
        $this->expediente = Expediente::where('alumno_id', $this->info_alumno->id_alumno)->get();
        foreach ($this->expediente as $expediente) {

            //Verificamos si el nivel ya termino (Si existe la carpeta constancias en el nivel)
            $info_nivel = DocumentoNivel::where('nivel_id', $expediente->nivel_id)->first();
            $info_nivel == null ? $carpetas = '' : $carpetas = Storage::allDirectories($info_nivel->ruta_doc);
            $nivel_finalizado = collect($carpetas)->contains(function ($carpeta) {
                return basename($carpeta) === 'Constancias';
            });

            if ($nivel_finalizado) {
                //Sacamos la constancia del alumno
                $carpeta =  Storage::directories($info_nivel->ruta_doc);
                $matricula = $this->info_alumno->matricula;
                $subcarpetas = Storage::allDirectories($info_nivel->ruta_doc);
                $expediente->ruta_const = collect($subcarpetas)->first(function ($carpeta) use ($matricula) {
                    return Str::startsWith(basename($carpeta), $matricula);
                });
                if ($expediente->ruta_const != null) {
                    $expediente->ruta_const = collect(Storage::files($expediente->ruta_const))->first();
                }

                //Parametro para indicar que el nivel finalizó
                $expediente->finalizado = 1;
                $this->tabla=true;
            } else {
                $expediente->finalizado = 0;
            }
            $info_nivel = Nivel::find($expediente->nivel_id);
            $expediente->nivel_texto = $info_nivel->nivel;
            $expediente->grupo_texto = $info_nivel->nombre_grupo;
            $expediente->periodo_texto = $info_nivel->periodo->periodo;
            $expediente->maestro_texto = $info_nivel->profesor->nombre . ' ' . $info_nivel->profesor->ap_paterno;



            $nota_nivel = Nota::where('nivel_id', $expediente->nivel_id)->where('alumno_id', $this->info_alumno->id_alumno)->first();
            $expediente->nota_parcial_1_texto = $nota_nivel->nota_parcial_1;
            $expediente->nota_parcial_2_texto = $nota_nivel->nota_parcial_2;
            $expediente->nota_parcial_3_texto = $nota_nivel->nota_parcial_3;
            $expediente->nota_final_texto = number_format($nota_nivel->promedio, 2);
        }
    }


    public function render()
    {
        return view('livewire.alumno.nivelesanteriores');
    }
}
