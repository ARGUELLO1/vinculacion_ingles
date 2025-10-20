<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $rules = [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id_user, 'id_user'),
            ],
        ];

        // Campos comunes para todos los usuarios
        $rules['name'] = ['required', 'string', 'max:50'];
        $rules['ap_paterno'] = ['required', 'string', 'max:50'];
        $rules['ap_materno'] = ['required', 'string', 'max:50'];

        // Reglas específicas por rol
        switch ($user->rol_id) {
            case 4: // Alumno
                $rules = array_merge($rules, $this->alumnoRules());
                break;
            case 3: // Profesor
                $rules = array_merge($rules, $this->profesorRules());
                break;
            case 1: // Admin
            case 2: // Capturista
                $rules = array_merge($rules, $this->adminRules());
                break;
        }

        return $rules;
    }

    /**
     * Reglas de validación para Alumno
     */
    protected function alumnoRules(): array
    {
        return [
            'matricula' => ['required', 'string', 'max:10', 'regex:/^[A-Z0-9]+$/'],
            'edad' => ['required', 'integer', 'min:16', 'max:100'],
            'telefono' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
            'sexo' => ['required', 'in:M,F'],
            'carrera_id' => ['required', 'exists:carreras,id_carrera'],
        ];
    }

    /**
     * Reglas de validación para Profesor
     */
    protected function profesorRules(): array
    {
        return [
            'rfc' => ['required', 'string', 'max:13', 'regex:/^[A-Z0-9]+$/'],
            'edad' => ['required', 'integer', 'min:18', 'max:100'],
            'sexo' => ['required', 'in:M,F'],
            'calle' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'colonia' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'size:5', 'regex:/^[0-9]+$/'],
            'municipio_id' => ['required', 'exists:municipios,id_municipio'],
            'estado' => ['required', 'string', 'max:50'],
            'estado_civil_id' => ['required', 'exists:estados_civiles,id_estado_civil'],
        ];
    }

    /**
     * Reglas de validación para Admin y Capturista
     */
    protected function adminRules(): array
    {
        return [
            // Campos adicionales para admin/capturista si los necesitan
        ];
    }

    /**
     * Mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'matricula.regex' => 'La matrícula solo puede contener letras mayúsculas y números.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'rfc.regex' => 'El RFC solo puede contener letras mayúsculas y números.',
            'codigo_postal.regex' => 'El código postal solo puede contener números.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'municipio_id.exists' => 'El municipio seleccionado no es válido.',
            'estado_civil_id.exists' => 'El estado civil seleccionado no es válido.',
        ];
    }

    /**
     * Preparar los datos para la validación
     */
    protected function prepareForValidation(): void
    {
        // Convertir a mayúsculas antes de validar
        if ($this->has('matricula')) {
            $this->merge([
                'matricula' => strtoupper($this->matricula),
            ]);
        }

        if ($this->has('rfc')) {
            $this->merge([
                'rfc' => strtoupper($this->rfc),
            ]);
        }
    }
}
