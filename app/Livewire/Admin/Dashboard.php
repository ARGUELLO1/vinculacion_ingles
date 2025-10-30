<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Dashboard extends Component
{
    public $showEmailWarning = false;
    public $showPasswordWarning = false;
    public $showNameWarning = false;

    public function mount()
    {
        $user = auth()->user();

        // Verificar correo por defecto
        if ($user->email === 'admin@admin.com') {
            $this->showEmailWarning = true;
        }

        // Verificar contraseña por defecto
        if (Hash::check('@dmin123', $user->password)) {
            $this->showPasswordWarning = true;
        }

        // Verificar nombre por defecto (puedes ajustar el nombre)
        if ($user->name === 'Admin' || $user->name === 'Administrador') {
            $this->showNameWarning = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
