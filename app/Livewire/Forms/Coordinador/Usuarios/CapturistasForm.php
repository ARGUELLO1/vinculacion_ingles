<?php

namespace App\Livewire\Forms\Coordinador\Usuarios;

use App\Models\Capturista;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CapturistasForm extends Form
{
    public ?Capturista $capturistaEdit = null;
    public ?User $userEdit = null;

    #[Validate('required|string|max:255', as: 'Nombre del Coordinador')]
    public string $name = '';

    #[Validate('required|string|max:255', as: 'Apellido Paterno')]
    public string $ap_paterno = '';

    #[Validate('required|string|max:255', as: 'Apellido Materno')]
    public string $ap_materno = '';

    #[Validate('required|email|lowercase|max:255|unique:' . User::class, as: 'Email')]
    public string $email = '';

    #[Validate('required|string|confirmed', as: 'Contraseña')]
    public string $password = '';
    public string $password_confirmation = '';

    public $capturistaName;
    public $capturistaID;

    public function setCapturista(Capturista $capturista)
    {
        $this->capturistaEdit = $capturista;
        $this->userEdit = $capturista->user;

        $this->name = $capturista->user->name;
        $this->ap_paterno = $capturista->ap_paterno;
        $this->ap_materno = $capturista->ap_materno;
        $this->email = $capturista->user->email;
    }

    public function store()
    {
        $this->validate([
            'password' => [Rules\Password::defaults()]
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        $user->assignRole('capturista');

        $this->capturistaName = Capturista::create([
            'user_id' => $user->id,
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno
        ]);

        event(new Registered($user));
    }

    public function update()
    {
        $this->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|lowercase|max:255|unique:users,email,' . $this->userEdit->id,
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'required|string|max:255',
        ]);

        $this->userEdit->update([
            'name' => $this->name,
            'email' => $this->email
        ]);

        $this->capturistaEdit->update([
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno
        ]);

        $this->capturistaID = $this->capturistaEdit;
    }
}
