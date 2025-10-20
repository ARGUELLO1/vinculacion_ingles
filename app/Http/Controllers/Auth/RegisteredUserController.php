<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'matricula' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Usar transacción para asegurar que ambos registros se creen
        $user = DB::transaction(function () use ($request) {
            // Crear User (solo email y password)
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => 4, // Rol alumno
            ]);

            // Crear Alumno (con el name)
            Alumno::create([
                'matricula' => $request->matricula,
                'nombre' => $request->name, // Aquí va el name del formulario
                'user_id' => $user->id_user,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        // Redirigir directamente al edit profile
        return redirect()->route('profile.edit')->with([
            'status' => 'complete-profile',
            'message' => 'Por favor completa tu información de perfil'
        ]);
    }
}
