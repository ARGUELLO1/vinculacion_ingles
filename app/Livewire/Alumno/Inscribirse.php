<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Inscribirse extends Component
{

    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;

    //INFORMACIÓN DEL FORMULARIO
    public $linea_captura, $fecha_pago, $fecha_entrega, $nivel_cursar;

    //DOCUMENTOS DEL FORMULARIO
    public $solicitud_aspitante_doc, $linea_captura_doc, $comprobante_pago_doc, $ine_doc, $acta_nacimiento_doc, $comprobante_estudio_doc;


    public function mount()
    {
        $this->info_alumno = Auth::user()->alumno->id_alumno;
    }
    public function render()
    {
        return view('livewire.alumno.inscribirse');
    }
}
