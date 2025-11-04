<?php

namespace App\Livewire\Admin\Permisos;

use App\Livewire\Forms\AdminCoordinador\Permisos\PermisosForm;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public PermisosForm $form;
    public $user;

    public function mount($usuario = null)
    {
        if ($usuario) {
            $this->user = User::with('permissions')->findOrFail($usuario);
            $this->form->setUser($this->user);
        }
    }

    public function save()
    {
        $this->form->save();

        session()->flash('message', 'Permisos del usuario ' . $this->form->usuarioName . ' actualizados correctamente.');

        // Redirigir de vuelta a la lista
        $this->redirectRoute('admin.permisos.index', navigate: true);
    }

    public function selectAllInGroup($groupKey)
    {
        $this->form->selectAllInGroup($groupKey);
    }

    public function clearAllInGroup($groupKey)
    {
        $this->form->clearAllInGroup($groupKey);
    }

    public function render()
    {
        return view('livewire.admin.permisos.create');
    }
}
