<?php

namespace App\Livewire\Alumno;

use App\Livewire\Forms\Alumno\ReinscribirseForm;
use App\Models\Alumno;
use App\Models\DocumentoExpediente;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
use App\Models\Nivel;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Reinscribirse extends Component
{
    //IMPORTANTE PARA EL FORMULARIO
    use WithFileUploads;
    public $bloquear_nivel = false;

    //INFORMACIÓN DEL FORMULARIO
    public ReinscribirseForm $info_formulario;

    public $constancias_termino = [];

    public function mount()
    {
        $this->info_formulario->info_alumno = Auth::user()->alumno;

        //Este registro es para saber si el grupo ya finalizó, si se encuentran constancias.
        if ($this->info_formulario->info_alumno->nivel) {
            $this->constancias_termino = DocumentoNivel::where('nivel_id', $this->info_formulario->info_alumno->nivel->id_nivel)->get();
        }
    }


    //Actualiza el <input> que muestra los grupos dependiendo del número de nivel seleccionado
    public function updatedInfoFormularioNivelcursar($nivel)
    {
        $this->info_formulario->grupos = Nivel::where('nivel', $nivel)->get();

        foreach ($this->info_formulario->grupos as $grupo) {
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
        $this->info_formulario->validate();
        $this->info_formulario->save();
        redirect()->route('Alumno.principal');
    }

    public function render()
    {
        return view('livewire.alumno.reinscribirse');
    }
}
