<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirección según el rol del usuario
        return $this->redirectToDashboard();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redireccionamiento al dashboard según el rol
     */
    protected function redirectToDashboard(): RedirectResponse
    {
        $user = Auth::user();

        // Verificar si el perfil está completo
        if (!$this->isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with([
                'status' => 'profile-incomplete',
                'message' => 'Por favor completa tu información de perfil'
            ]);
        }

        // Si el perfil está completo, redirigir según el rol
        switch ($user->rol_id) {
            case 1: // Admin
                return redirect()->intended(route('admin.panel', absolute: false));
            case 2: // Capturista
                return redirect()->intended(route('capturista.panel', absolute: false));
            case 3: // Profesor
                return redirect()->intended(route('profesor.panel', absolute: false));
            case 4: // Alumno
                return redirect()->intended(route('alumno.panel', absolute: false));
            default:
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Verificar si el perfil del usuario está completo
     */
    /**
     * Verificar si el perfil del usuario está completo
     */
    protected function isProfileComplete($user): bool
    {
        // Si no tiene perfil relacionado, no está completo
        if (!$user->perfil()) {
            return false;
        }

        $perfil = $user->perfil();

        // Campos básicos requeridos para todos
        $basicFields = ['nombre', 'ap_paterno', 'ap_materno'];

        foreach ($basicFields as $field) {
            if (empty($perfil->$field)) {
                return false;
            }
        }

        // Campos específicos por rol
        switch ($user->rol_id) {
            case 3: // Profesor
                $profesorFields = ['rfc', 'edad', 'sexo'];
                foreach ($profesorFields as $field) {
                    if (empty($perfil->$field)) {
                        return false;
                    }
                }
                break;

            case 4: // Alumno
                $alumnoFields = ['edad', 'sexo', 'telefono', 'carrera_id'];
                foreach ($alumnoFields as $field) {
                    if (empty($perfil->$field)) {
                        return false;
                    }
                }
                break;
        }

        return true;
    }
}