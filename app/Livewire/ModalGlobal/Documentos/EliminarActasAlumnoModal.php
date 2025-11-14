<?php

namespace App\Livewire\ModalGlobal\Documentos;

use App\Models\Nivel;
use Livewire\Component;

class EliminarActasAlumnoModal extends Component
{
    public $isOpen = false;
    public $alumnoId;
    public $alumnoNombre;
    public $cantidadConstancias;

    protected $listeners = ['abrirModalEliminarAlumno' => 'abrirModal'];

    public function abrirModal($alumnoId)
    {
        // Asegurarse de que $alumnoId es un valor único, no un array
        if (is_array($alumnoId)) {
            $alumnoId = $alumnoId[0] ?? null;
        }

        $this->alumnoId = $alumnoId;
        $alumno = Nivel::withCount('documentosNiveles')->find($alumnoId);

        if ($alumno) {
            $this->alumnoNombre = $alumno->nombre_completo;
            $this->cantidadConstancias = $alumno->constancias_count;
            $this->isOpen = true;
        }
    }

    public function cerrarModal()
    {
        $this->isOpen = false;
        $this->reset(['alumnoId', 'alumnoNombre', 'cantidadConstancias']);
    }

    public function eliminarConstancias()
    {
        try {
            // Emitir evento al componente principal para eliminar las constancias
            $this->dispatch('eliminarConstanciasAlumnoConfirmado', $this->alumnoId);
            $this->cerrarModal();
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error al eliminar las constancias: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.modal-global.documentos.eliminar-actas-alumno-modal');
    }
}