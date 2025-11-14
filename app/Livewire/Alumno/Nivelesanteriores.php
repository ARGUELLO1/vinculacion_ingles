<?php

namespace App\Livewire\Alumno;

use App\Models\DocumentoExpediente;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
use App\Models\Nivel;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NivelesAnteriores extends Component
{
    //Información del alumno
    public $info_alumno;
    public $expediente;
    public $niveles;
    public $documentos_nivel;

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
            $nivel_finalizado = DocumentoNivel::where('nivel_id', $expediente->nivel_id)->first() ?? '';
            if (!$nivel_finalizado == '') {
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
    }


    public function render()
    {
        return view('livewire.alumno.nivelesanteriores');
    }
}
