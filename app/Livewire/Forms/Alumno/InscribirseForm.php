<?php

namespace App\Livewire\Forms;

namespace App\Livewire\Forms\Alumno;

use App\Models\Alumno;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\Nivel;
use App\Models\Nota;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InscribirseForm extends Form
{
    //INFORMACIÓN DEL ALUMNO

    public $info_alumno;
    public $grupos = [];

    //INFORMACIÓN DEL FORMULARIO
    #[Rule('required|unique:expedientes,lin_captura_t')]
    public $linea_captura;

    #[Rule('required')]
    public $fecha_pago;

    #[Rule('required')]
    public $fecha_entrega;

    #[Rule('required')]
    public $nivel_cursar = '';

    #[Rule('required')]
    public $grupo_cursar = '';


    //DOCUMENTOS DEL FORMULARIO
    #[Rule([
        'documentos.solicitud_aspirante_doc' => 'required|file|mimes:pdf|max:500',
        'documentos.linea_captura_doc' => 'required|file|mimes:pdf|max:500',
        'documentos.comprobante_pago_doc' => 'required|file|mimes:pdf|max:500',
        'documentos.ine_doc' => 'required|file|mimes:pdf|max:500',
        'documentos.acta_nacimiento_doc' => 'required|file|mimes:pdf|max:500',
        'documentos.comprobante_estudio_doc' => 'required|file|mimes:pdf|max:500',
    ])]
    public $documentos = [
        'solicitud_aspirante_doc' => '',
        'linea_captura_doc' => '',
        'comprobante_pago_doc' => '',
        'ine_doc' => '',
        'acta_nacimiento_doc' => '',
        'comprobante_estudio_doc' => ''
    ];


    public function save()
    {
        //Se le asigna al alumno el id del  grupo a cursar 
        $inscripcion_alumno = Alumno::find($this->info_alumno->id_alumno);
        $inscripcion_alumno->nivel_id = $this->grupo_cursar;
        $inscripcion_alumno->update();

        $nivel = Nivel::find($this->grupo_cursar);

        //Se crea el registro para las calificaciones
        $calificacion = new Nota();
        $calificacion->alumno_id = $inscripcion_alumno->id_alumno;
        $calificacion->nivel_id = $this->grupo_cursar;
        $calificacion->nota_parcial_1 = 0;
        $calificacion->nota_parcial_2 = 0;
        $calificacion->nota_parcial_3 = 0;
        $calificacion->save();


        //Creamos la carpeta del alumno con la id alumno y dentro creamos una carpeta colocando el id_nivel como nombre
        $ruta_expediente = "expedientes_alumno/" . $this->info_alumno->id_alumno . "_" . $this->info_alumno->matricula . "/" . $this->grupo_cursar . "_" . $nivel->nombre_grupo;
        // Creamos la carpeta
        Storage::makeDirectory($ruta_expediente);


        //Se guarda el registro en la tabla expediente
        $expediente = new Expediente();
        $expediente->alumno_id = $this->info_alumno->id_alumno;
        $expediente->nivel_id = $this->grupo_cursar;
        $expediente->ruta_expediente = $ruta_expediente;
        $expediente->lin_captura_t = $this->linea_captura;
        $expediente->fecha_pago = $this->fecha_pago;
        $expediente->fecha_entrega = $this->fecha_entrega;
        $expediente->save();

        //Guardamos los documentos del formulario en la tabla correspondiente ("documentos_expedientes")
        foreach ($this->documentos as $tipo_doc => $archivo) {

            //Obtenemos la extensión del archivo
            if ($archivo) {
                $extension = $archivo->getClientOriginalExtension();

                //Creamos el nombre del archivo
                $nombreArchivo = $tipo_doc . '.' . $extension;

                $ruta_guardado = $archivo->storeAs($ruta_expediente, $nombreArchivo, 'local');

                $documentos_expediente = new DocumentoExpediente();
                $documentos_expediente->id_expediente = $expediente->id_expediente;
                $documentos_expediente->tipo_doc = $tipo_doc;
                $documentos_expediente->ruta_doc = $ruta_guardado;
                $documentos_expediente->save();
            }
        }
    }
}
