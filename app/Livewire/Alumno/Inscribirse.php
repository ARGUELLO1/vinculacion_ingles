<?php

namespace App\Livewire\Alumno;

use App\Models\Alumno;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\Nivel;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Inscribirse extends Component
{
    //IMPORTANTE PARA EL FORMULARIO
    use WithFileUploads;
    public $grupos = [];
    public $cantidad_alumnos = [];
    public $bloquear_nivel = false;


    //INFORMACIÓN DEL ALUMNO
    public $info_alumno;



    //INFORMACIÓN DEL FORMULARIO
    public $info_formulario = [
        'linea_captura' => '',
        'fecha_pago' => '',
        'fecha_entrega' => '',
        'nivel_cursar' => '',
        'grupo_cursar' => ''
    ];

    //DOCUMENTOS DEL FORMULARIO
    public $documentos_formulario = [
        'solicitud_aspirante_doc' => '',
        'linea_captura_doc' => '',
        'comprobante_pago_doc' => '',
        'ine_doc' => '',
        'acta_nacimiento_doc' => '',
        'comprobante_estudio_doc' => '',
    ];

    public function mount()
    {
        $this->info_alumno = Auth::user()->alumno;
    }

    //Actualiza el <input> que muestra los grupos dependiendo del número de nivel seleccionado
    public function updatedInfoFormularioNivelcursar($nivel)
    {
        $this->grupos = [];
        $this->info_formulario['grupo_cursar'] = '';

        $this->grupos = Nivel::where('nivel', $nivel)->get();

        foreach ($this->grupos as $grupo) {
            $grupo->cantidad_alumnos = Alumno::where("nivel_id", $grupo->id_nivel)->count();
        }
    }

    //Bloquea el input de seleccionar nivel (Se realizó por que hay un error con el id_nivel al dar libertad de cambiar niveles)
    public function updatedInfoFormularioGrupoCursar($value)
    {
        if (!empty($value)) {
            $this->bloquear_nivel = true;
        }
    }


    //Guarda en la base de datos, los datos y archivos que se obtuvieron del formulariow
    public function save()
    {
        //Se le asigna al alumno el id del  grupo a cursar 
        $inscripcion_alumno = Alumno::find($this->info_alumno->id_alumno);
        $inscripcion_alumno->nivel_id = $this->info_formulario['grupo_cursar'];
        $inscripcion_alumno->update();

        $nivel = Nivel::find($this->info_formulario['grupo_cursar']);

        //Se crea el registro para las calificaciones
        $calificacion = new Nota();
        $calificacion->alumno_id = $inscripcion_alumno->id_alumno;
        $calificacion->nivel_id = $this->info_formulario['grupo_cursar'];
        $calificacion->nota_parcial_1 = 0;
        $calificacion->nota_parcial_2 = 0;
        $calificacion->nota_parcial_3 = 0;
        $calificacion->save();


        //Creamos la carpeta del alumno con la id alumno y dentro creamos una carpeta colocando el id_nivel como nombre
        $ruta_expediente = "expedientes_alumno/" . $this->info_alumno->id_alumno . "_" . $this->info_alumno->matricula . "/" . $this->info_formulario['grupo_cursar'] . "_" . $nivel->nombre_grupo;
        // Creamos la carpeta
        Storage::makeDirectory($ruta_expediente);


        //Se guarda el registro en la tabla expediente
        $expediente = new Expediente();
        $expediente->alumno_id = $this->info_alumno->id_alumno;
        $expediente->nivel_id = $this->info_formulario['grupo_cursar'];
        $expediente->ruta_expediente = $ruta_expediente;
        $expediente->lin_captura_t = $this->info_formulario['linea_captura'];
        $expediente->fecha_pago = $this->info_formulario['fecha_pago'];
        $expediente->fecha_entrega = $this->info_formulario['fecha_entrega'];
        $expediente->save();

        //Guardamos los documentos del formulario en la tabla correspondiente ("documentos_expedientes")
        foreach ($this->documentos_formulario as $tipo_doc => $archivo) {

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

        redirect()->route('Alumno.principal');
    }

    public function render()
    {
        return view('livewire.alumno.inscribirse');
    }
}
