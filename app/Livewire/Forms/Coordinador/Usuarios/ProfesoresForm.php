<?php

namespace App\Livewire\Forms\Coordinador\Usuarios;

use App\Models\Profesor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfesoresForm extends Form
{
    public ?Profesor $profesorEdit = null;
    public ?User $userEdit = null;

    #[Validate('required|string|max:255', as: 'Nombre del Coordinador')]
    public string $name = '';

    #[Validate('required|string|max:255', as: 'Apellido Paterno')]
    public string $ap_paterno = '';

    #[Validate('required|string|max:255', as: 'Apellido Materno')]
    public string $ap_materno = '';

    #[Validate('required|in:activo,inactivo', as: 'Estatus')]
    public string $estatus = '';

    #[Validate('required|in:M,F', as: 'Sexo')]
    public string $sexo = '';

    #[Validate('required|email|lowercase|max:255|unique:' . User::class, as: 'Email')]
    public string $email = '';

    #[Validate('required|string|confirmed', as: 'Contraseña')]
    public string $password = '';
    public string $password_confirmation = '';


    public $profesorName;
    public $profesorID;

    public function setProfesor(Profesor $profesor)
    {
        $this->profesorEdit = $profesor;
        $this->userEdit = $profesor->user;

        $this->name = $profesor->user->name;
        $this->ap_paterno = $profesor->ap_paterno;
        $this->ap_materno = $profesor->ap_materno;
        $this->estatus = $profesor->estatus;
        $this->sexo = $profesor->sexo;
        $this->email = $profesor->user->email;
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

        $user->assignRole('profesor');

        $this->profesorName = Profesor::create([
            'user_id' => $user->id,
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno,
            'estatus' => $this->estatus,
            'sexo' => $this->sexo
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

        $this->profesorEdit->update([
            'nombre' => $this->name,
            'ap_paterno' => $this->ap_paterno,
            'ap_materno' => $this->ap_materno,
            'estatus' => $this->estatus,
            'sexo' => $this->sexo
        ]);

        $this->profesorID = $this->profesorEdit;
    }
}
