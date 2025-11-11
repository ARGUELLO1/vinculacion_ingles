<?php

namespace App\Livewire\Alumno;

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

    public function mount()
    {
        //Información del alumno
        $this->info_alumno = Auth::user()->alumno;

        //Información de expedientes, se sacan todos los expedientes ya que corresponde a los niveles cursados por el alumno
        $this->expediente = Expediente::where('alumno_id', $this->info_alumno->id_alumno)->get();
        foreach ($this->expediente as $expediente) {
            //dd($expediente->documentosExpedientes);

            $nivel_finalizado = DocumentoNivel::where('nivel_id', $expediente->nivel_id)->first() ?? '';
            if (!$nivel_finalizado == '') {
                $info_nivel = Nivel::find($expediente->nivel_id);
                $expediente->nivel = $info_nivel->nivel;
                $expediente->grupo = $info_nivel->nombre_grupo;
                $expediente->periodo = $info_nivel->periodo->periodo;
                $expediente->maestro = $info_nivel->profesor->nombre . ' ' . $info_nivel->profesor->ap_paterno;

                $nota_nivel = Nota::where('nivel_id', $expediente->nivel_id)->where('alumno_id', $this->info_alumno->id_alumno)->first();
                $expediente->nota_parcial_1 = $nota_nivel->nota_parcial_1;
                $expediente->nota_parcial_2 = $nota_nivel->nota_parcial_2;
                $expediente->nota_parcial_3 = $nota_nivel->nota_parcial_3;
                $expediente->nota_final = number_format($nota_nivel->promedio, 2);
            }
        }
    }




    public function render()
    {
        return view('livewire.alumno.nivelesanteriores');
    }
}
