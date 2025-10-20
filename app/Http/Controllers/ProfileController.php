<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $perfilData = [];
        $additionalData = [];

        // Cargar datos según el rol
        if ($user->esAlumno() && $user->alumno) {
            $perfilData = $user->alumno;
            $additionalData['carreras'] = \App\Models\Carrera::all(); // Para el select de carreras
        } elseif ($user->esProfesor() && $user->profesor) {
            $perfilData = $user->profesor;
            $additionalData['municipios'] = \App\Models\Municipio::all();
            $additionalData['estados_civiles'] = \App\Models\Estado_Civil::all();
        } elseif (($user->esAdministrador() || $user->esCapturista()) && $user->administrador) {
            $perfilData = $user->administrador;
        }

        return view('profile.edit', array_merge([
            'user' => $user,
            'perfilData' => $perfilData,
        ], $additionalData));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Usar transacción para asegurar la consistencia de datos
        DB::transaction(function () use ($request, $user) {
            // Actualizar datos básicos del usuario
            $user->fill([
                'email' => $request->email,
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Actualizar datos del perfil específico
            $this->updateProfileData($request, $user);
        });

        // Verificar si el perfil ahora está completo y redirigir al dashboard
        if ($this->isProfileComplete($user)) {
            return $this->redirectToDashboardAfterUpdate($user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Redirección al dashboard después de actualizar el perfil
     */
    protected function redirectToDashboardAfterUpdate($user): RedirectResponse
    {
        $nombreCompleto = $user->nombre_completo;

        switch ($user->rol_id) {
            case 1: // Admin
                return redirect()->route('admin.panel')
                    ->with('success', "¡Datos actualizados correctamente!");
            case 2: // Capturista
                return redirect()->route('capturista.panel')
                    ->with('success', "¡Datos actualizados correctamente!");
            case 3: // Profesor
                return redirect()->route('profesor.panel')
                    ->with('success', "¡Datos actualizados correctamente!");
            case 4: // Alumno
                return redirect()->route('alumno.panel')
                    ->with('success', "¡Datos actualizados correctamente!");
            default:
                return redirect()->route('dashboard')
                    ->with('success', "¡Datos actualizados correctamente!");
        }
    }

    /**
     * Actualizar los datos del perfil específico según el rol
     */
    protected function updateProfileData(ProfileUpdateRequest $request, $user): void
    {
        $profileData = [
            'nombre' => $request->name,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
        ];

        switch ($user->rol_id) {
            case 4: // Alumno
                if ($user->alumno) {
                    $alumnoData = array_merge($profileData, [
                        'matricula' => $request->matricula,
                        'edad' => $request->edad,
                        'telefono' => $request->telefono,
                        'sexo' => $request->sexo,
                        'carrera_id' => $request->carrera_id,
                    ]);
                    $user->alumno->update($alumnoData);
                }
                break;

            case 3: // Profesor
                if ($user->profesor) {
                    $profesorData = array_merge($profileData, [
                        'edad' => $request->edad,
                        'sexo' => $request->sexo,
                        'rfc' => $request->rfc,
                        'calle' => $request->calle,
                        'numero' => $request->numero,
                        'colonia' => $request->colonia,
                        'codigo_postal' => $request->codigo_postal,
                        'municipio_id' => $request->municipio_id,
                        'estado' => $request->estado,
                        'estado_civil_id' => $request->estado_civil_id,
                    ]);
                    $user->profesor->update($profesorData);
                }
                break;

            case 1: // Admin
            case 2: // Capturista
                if ($user->administrador) {
                    $user->administrador->update($profileData);
                }
                break;
        }
    }

    /**
     * Verificar si el perfil está completo (mismo método que en el login)
     */
    protected function isProfileComplete($user): bool
    {
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

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Eliminar también el perfil específico
        DB::transaction(function () use ($user) {
            if ($user->alumno) {
                $user->alumno->delete();
            } elseif ($user->profesor) {
                $user->profesor->delete();
            } elseif ($user->administrador) {
                $user->administrador->delete();
            }

            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
