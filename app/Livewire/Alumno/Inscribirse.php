<?php

namespace App\Livewire\Alumno;

use App\Livewire\Forms\Alumno\InscribirseForm;
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
    public $bloquear_nivel = false;



    //FORMULARIO
    public InscribirseForm $info_formulario;

    public function mount()
    {
        $this->info_formulario->info_alumno = Auth::user()->alumno;
    }

    //Bloquea el input de seleccionar nivel (Se realizó por que hay un error con el id_nivel al dar libertad de cambiar niveles)
    public function updatedInfoFormularioGrupoCursar($value)
    {
        if (!empty($value)) {
            $this->bloquear_nivel = true;
        }
    }

    //Actualiza el <input> que muestra los grupos dependiendo del número de nivel seleccionado
    public function updatedInfoFormularioNivelCursar($nivel)
    {
        $this->info_formulario->grupo_cursar = '';
        $this->info_formulario->grupos = Nivel::where('nivel', $nivel)->get();

        foreach ($this->info_formulario->grupos as $grupo) {
            $grupo->cantidad_alumnos = Alumno::where('nivel_id', $grupo->id_nivel)->count();
        }
    }


    //Guarda en la base de datos, los datos y archivos que se obtuvieron del formulariow
    public function save()
    {
        $this->info_formulario->validate();
        $this->info_formulario->save();
        $this->info_formulario->reset();
        redirect()->route('Alumno.principal');
    }

    public function render()
    {
        return view('livewire.alumno.inscribirse');
    }
}
