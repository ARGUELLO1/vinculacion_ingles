<?php

namespace App\Livewire\Forms\AdminCoordinador\Usuarios;

use App\Models\Coordinador;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CoordinadoresForm extends Form
{
    public ?Coordinador $coordinadorEdit = null;
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

    public $coordinadorName;
    public $coordinadorID;

    public function setCoordinador(Coordinador $coordinador)
    {
        $this->coordinadorEdit = $coordinador;
        $this->userEdit = $coordinador->user;

        $this->name = $coordinador->user->name;
        $this->ap_paterno = $coordinador->ap_paterno;
        $this->ap_materno = $coordinador->ap_materno;
        $this->email = $coordinador->user->email;
    }

    public function store()
    {
        $this->validate([
            'password' => [Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('coordinador');

        $this->coordinadorName = Coordinador::create([
            'user_id' => $user->id,
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno,
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
            'email' => $this->email,
        ]);

        $this->coordinadorEdit->update([
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno,
        ]);

        $this->coordinadorID = $this->coordinadorEdit;
    }
}
