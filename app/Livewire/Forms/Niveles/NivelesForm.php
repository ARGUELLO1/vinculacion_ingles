<?php

namespace App\Livewire\Forms\Niveles;

use App\Models\Nivel;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NivelesForm extends Form
{
    public ?Nivel $nivelEdit = null;

    #[Validate('required|string|max:3', as: 'Nivel')]
    public string $nivel = '';

    #[Validate('required|string|max:4', as: 'Nombre del Grupo')]
    public string $grupo = '';

    #[Validate('required|string|max:255', as: 'Aula')]
    public string $aula = '';

    #[Validate('required|integer|min:1|max:100', as: 'Cupo Maximo')]
    public string $cupo_max = '';

    #[Validate('required|date_format:H:i', as: 'Hora de Entrada')]
    public string $hora_entrada = '';

    #[Validate('required|date_format:H:i', as: 'Hora de Salida')]
    public string $hora_salida = '';

    #[Validate('required|exists:profesores,id_profesor', as: 'Profesor')]
    public string $profesor = '';

    #[Validate('required|exists:periodos,id_periodo', as: 'Periodo')]
    public string $periodo = '';

    #[Validate('required|exists:modalidades,id_modalidad', as: 'Modalidad')]
    public string $modalidad = '';

    public $nivelName;
    public $nivelGrupo;

    public function setNivel(Nivel $niveles)
    {
        $this->nivelEdit = $niveles;

        $this->nivel = $niveles->nivel;
        $this->grupo = $niveles->nombre_grupo;
        $this->aula = $niveles->aula;
        $this->cupo_max = $niveles->cupo_max;

        //Separar el horario de la base de datos en hora de entrada y hora de salida
        $horarioParts = explode(' - ', $niveles->horario);
        $this->hora_entrada = $horarioParts[0] ?? '';
        $this->hora_salida = $horarioParts[1] ?? '';

        $this->profesor = $niveles->profesor_id;
        $this->periodo = $niveles->periodo_id;
        $this->modalidad = $niveles->modalidad_id;
    }

    public function store()
    {
        $this->validate();

        $this->nivelName = Nivel::create([
            'nivel' => $this->nivel,
            'nombre_grupo' => $this->grupo,
            'aula' => $this->aula,
            'cupo_max' => $this->cupo_max,
            'horario' => $this->hora_entrada . ' - ' . $this->hora_salida,
            'profesor_id' => $this->profesor,
            'periodo_id' => $this->periodo,
            'modalidad_id' => $this->modalidad,
            'parcial_1' => '1',
            'parcial_2' => '0',
            'parcial_3' => '0',
        ]);
    }

    public function update()
    {
        $this->validate();

        $this->nivelEdit->update([
            'nivel' => $this->nivel,
            'nombre_grupo' => $this->grupo,
            'aula' => $this->aula,
            'cupo_max' => $this->cupo_max,
            'horario' => $this->hora_entrada . ' - ' . $this->hora_salida,
            'profesor_id' => $this->profesor,
            'periodo_id' => $this->periodo,
            'modalidad_id' => $this->modalidad,
        ]);

        $this->nivelGrupo = $this->nivelEdit;
    }
}
