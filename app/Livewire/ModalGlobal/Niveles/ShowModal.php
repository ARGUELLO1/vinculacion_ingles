<?php

namespace App\Livewire\ModalGlobal\Niveles;

use App\Models\Nivel;
use Livewire\Component;

class ShowModal extends Component
{
    public $isOpen = false;
    public $nivelGrupo;
    public $aula;
    public $cupoMax;
    public $horario;
    public $nombreProfesor;
    public $periodo;
    public $modalidad;

    protected $listeners = ['openShowNivelModal' => 'openModal'];

    public function openModal($nivelId)
    {
        // Si el parámetro viene como array (cuando usas {nivelId: value})
        if (is_array($nivelId) && isset($nivelId['nivelId'])) {
            $nivelId = $nivelId['nivelId'];
        }

        // Buscar el nivel con sus relaciones
        $nivel = Nivel::with(['profesor', 'periodo', 'modalidad'])
            ->find($nivelId);

        if ($nivel) {
            // Asignar los datos a las propiedades
            $this->nivelGrupo = $nivel->nivel . ' - ' . $nivel->nombre_grupo;
            $this->aula = $nivel->aula;
            $this->cupoMax = $nivel->cupo_max;
            $this->horario = $nivel->horario;
            $this->nombreProfesor = $nivel->profesor->nombre_completo ?? $nivel->profesor->nombre ?? 'No asignado';
            $this->periodo = $nivel->periodo->periodo ?? 'No asignado';
            $this->modalidad = $nivel->modalidad->tipo_modalidad ?? 'No asignado';

            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetExcept('isOpen'); // Resetear todas las propiedades excepto isOpen
    }

    public function render()
    {
        return view('livewire.modal-global.niveles.show-modal');
    }
}
