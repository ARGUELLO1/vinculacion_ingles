<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $ap_paterno = '';
    public string $ap_materno = '';
    public string $email = '';

    //profesor campos

    public $edad;
    public $estado_civil_id;
    public $sexo;
    public $calle;
    public $numero;
    public $colonia;
    public $codigo_postal;
    public ?int $municipio_id = null;
    public $estado;
    public $rfc;
    public $estatus;

    //datos alumno
    public $matricula;
    public $edad_alumno;
    public $carrera_alumno;
    public $telefono_alumno;
    public $sexo_alumno;
    public $colonia_alumno;
    public $municipio_alumno;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = Auth::user()->name;

        // Cargar los apellidos desde la tabla específica según el rol
        $this->loadApellidosFromSpecificTable($user);

        $this->email = Auth::user()->email;
    }

    /**
     * Load apellidos from the specific user table based on role
     */
    protected function loadApellidosFromSpecificTable(User $user): void
    {
        $mainRole = $user->getRoleNames()->first();

        switch ($mainRole) {
            case 'admin':
                if ($user->administrador) {
                    $this->ap_paterno = $user->administrador->ap_paterno ?? '';
                    $this->ap_materno = $user->administrador->ap_materno ?? '';
                }
                break;

            case 'coordinador':
                if ($user->coordinador) {
                    $this->ap_paterno = $user->coordinador->ap_paterno ?? '';
                    $this->ap_materno = $user->coordinador->ap_materno ?? '';
                }
                break;

            case 'capturista':
                if ($user->capturista) {
                    $this->ap_paterno = $user->capturista->ap_paterno ?? '';
                    $this->ap_materno = $user->capturista->ap_materno ?? '';
                }
                break;

            case 'profesor':
                if ($user->profesor) {
                    $this->ap_paterno = $user->profesor->ap_paterno ?? '';
                    $this->ap_materno = $user->profesor->ap_materno ?? '';
                    $profesor = $user->profesor;
                    $this->edad = $profesor->edad;
                    $this->estado_civil_id = $profesor->estado_civil_id;
                    $this->sexo = $profesor->sexo;
                    $this->calle = $profesor->calle;
                    $this->numero = $profesor->numero;
                    $this->colonia = $profesor->colonia;
                    $this->codigo_postal = $profesor->codigo_postal;
                    $this->municipio_id = $profesor->municipio_id;
                    $this->estado = $profesor->estado;
                    $this->rfc = $profesor->rfc;
                    $this->estatus = $profesor->estatus;
                }
                break;

            case 'alumno':
                if ($user->alumno) {
                    $this->ap_paterno = $user->alumno->ap_paterno ?? '';
                    $this->ap_materno = $user->alumno->ap_materno ?? '';
                    $this->matricula = $user->alumno->matricula;
                    $this->edad_alumno = $user->alumno->edad ?? '';
                    $this->carrera_alumno = $user->alumno->carrera_id ?? '';
                    $this->telefono_alumno = $user->alumno->telefono ?? '';
                    $this->sexo_alumno = $user->alumno->sexo ?? '';
                    $this->colonia_alumno = $user->alumno->colonia ?? '';
                    $this->municipio_alumno = $user->alumno->municipio_id ?? '';
                }
                break;
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        $mainRole = $user->getRoleNames()->first();

        // 🔹 Validaciones base para todos los roles
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'ap_paterno' => ['required', 'string', 'max:255'],
            'ap_materno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ];

        // 🔹 Agregar validaciones según el rol
        if ($mainRole === 'profesor') {
            $rules = array_merge($rules, [
                'edad' => ['nullable', 'integer', 'min:18', 'max:100'],
                'sexo' => ['required', Rule::in(['M', 'F'])],
                'rfc' => ['nullable', 'string', 'max:13'],
                'codigo_postal' => ['nullable', 'digits:5'],
                'municipio_id' => ['required', 'exists:municipios,id_municipio'],
                'calle' => ['nullable', 'string', 'max:255'],
                'numero' => ['nullable', 'string', 'max:20'],
                'colonia' => ['nullable', 'string', 'max:255'],
                'estado' => ['nullable', 'string', 'max:50'],
                'estatus' => ['required', Rule::in(['activo', 'inactivo'])],
            ]);
        } elseif ($mainRole === 'alumno') {
            $rules = array_merge($rules, [
                'matricula' => ['required', 'nullable', 'string', 'max:10'],
                'edad_alumno' => ['required', 'nullable', 'integer', 'max:99', 'min:18'],
                'carrera_alumno' => ['required', 'nullable', 'integer'],
                'telefono_alumno' => ['required', 'nullable', 'string', 'max:11'],
                'sexo_alumno' => ['required', 'nullable', 'string', 'max:1'],
                'colonia_alumno' => ['required', 'nullable', 'string', 'max:255'],
                'municipio_alumno' => ['required', 'nullable', 'integer'],
            ]);
        }

        $validated = $this->validate($rules);

        // 🔹 Actualizar tabla users
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 🔹 Actualizar datos según el rol
        $this->updateUserSpecificData($user, $validated);

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Update user data in the specific table based on user role
     */
    protected function updateUserSpecificData(User $user, array $validated): void
    {
        // Obtener el rol principal del usuario
        $mainRole = $user->getRoleNames()->first();

        switch ($mainRole) {
            case 'admin':
                // Actualizar en tabla administradores
                if ($user->administrador) {
                    $user->administrador->update([
                        'nombre' => $validated['name'],
                        'ap_paterno' => $validated['ap_paterno'],
                        'ap_materno' => $validated['ap_materno'],
                    ]);
                }
                break;

            case 'coordinador':
                // Actualizar en tabla coordinadores
                if ($user->coordinador) {
                    $user->coordinador->update([
                        'nombre' => $validated['name'],
                        'ap_paterno' => $validated['ap_paterno'],
                        'ap_materno' => $validated['ap_materno'],
                    ]);
                }
                break;

            case 'capturista':
                // Actualizar en tabla capturistas
                if ($user->capturista) {
                    $user->capturista->update([
                        'nombre' => $validated['name'],
                        'ap_paterno' => $validated['ap_paterno'],
                        'ap_materno' => $validated['ap_materno'],
                    ]);
                }
                break;

            case 'profesor':
                // Actualizar en tabla profesores
                if ($user->profesor) {
                    $user->profesor->update([
                        'nombre' => $validated['name'],
                        'ap_paterno' => $validated['ap_paterno'],
                        'ap_materno' => $validated['ap_materno'],
                        'edad' => $this->edad,
                        'estado_civil_id' => $this->estado_civil_id,
                        'sexo' => $this->sexo,
                        'calle' => $this->calle,
                        'numero' => $this->numero,
                        'colonia' => $this->colonia,
                        'codigo_postal' => $this->codigo_postal,
                        'municipio_id' => $this->municipio_id,
                        'estado' => $this->estado,
                        'rfc' => $this->rfc,
                        'estatus' => $this->estatus ?? 'activo',
                    ]);
                }
                break;

            case 'alumno':
                // Actualizar en tabla alumnos
                if ($user->alumno) {
                    $user->alumno->update([
                        'nombre' => $validated['name'],
                        'ap_paterno' => $validated['ap_paterno'],
                        'ap_materno' => $validated['ap_materno'],
                        'matricula' => $this->matricula,
                        'edad' => $this->edad_alumno,
                        'carrera_id' => $this->carrera_alumno,
                        'telefono' => $this->telefono_alumno,
                        'sexo' => $this->sexo_alumno,
                        'colonia' => $this->colonia_alumno,
                        'municipio_id' => $this->municipio_alumno,
                    ]);
                }
                break;
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Actualiza tu información personal y dirección de correo electrónico.') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        {{-- Nombre --}}
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Apellido paterno --}}
        <div>
            <x-input-label for="ap_paterno" :value="__('Apellido Paterno')" />
            <x-text-input wire:model="ap_paterno" id="ap_paterno" name="ap_paterno" type="text"
                class="mt-1 block w-full" required autocomplete="ap_paterno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_paterno')" />
        </div>

        {{-- Apellido materno --}}
        <div>
            <x-input-label for="ap_materno" :value="__('Apellido Materno')" />
            <x-text-input wire:model="ap_materno" id="ap_materno" name="ap_materno" type="text"
                class="mt-1 block w-full" required autocomplete="ap_materno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_materno')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Tu dirección de correo no está verificada.') }}
                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- 🔹 Campos extra SOLO si el usuario es profesor --}}
        @if (auth()->user()->hasRole('profesor'))
            <hr class="my-6 border-gray-300">
            <h3 class="text-lg font-semibold text-gray-800">Información adicional del profesor</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="edad" :value="__('Edad')" />
                    <x-text-input wire:model="edad" id="edad" name="edad" type="number"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('edad')" />
                </div>

                <div>
                    <x-input-label for="sexo" :value="__('Sexo')" />
                    <select wire:model="sexo" id="sexo"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Seleccione...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
                </div>

                <div>
                    <x-input-label for="rfc" :value="__('RFC')" />
                    <x-text-input wire:model="rfc" id="rfc" name="rfc" type="text"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('rfc')" />
                </div>

                <div>
                    <x-input-label for="estado_civil_id" :value="__('Estado Civil')" />
                    <select wire:model="estado_civil_id" id="estado_civil_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Models\EstadoCivil::all() as $estadoCivil)
                            <option value="{{ $estadoCivil->id_estado_civil }}">{{ $estadoCivil->tipo_estado_civil }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('estado_civil_id')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="calle" :value="__('Calle')" />
                    <x-text-input wire:model="calle" id="calle" name="calle" type="text"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('calle')" />
                </div>

                <div>
                    <x-input-label for="numero" :value="__('Número')" />
                    <x-text-input wire:model="numero" id="numero" name="numero" type="text"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('numero')" />
                </div>

                <div>
                    <x-input-label for="colonia" :value="__('Colonia')" />
                    <x-text-input wire:model="colonia" id="colonia" name="colonia" type="text"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('colonia')" />
                </div>

                <div>
                    <x-input-label for="codigo_postal" :value="__('Código Postal')" />
                    <x-text-input wire:model="codigo_postal" id="codigo_postal" name="codigo_postal" type="text"
                        maxlength="5" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('codigo_postal')" />
                </div>

                <div>
                    <x-input-label for="estado" :value="__('Estado')" />
                    <x-text-input wire:model="estado" id="estado" name="estado" type="text"
                        class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('estado')" />
                </div>
                <div>
                    <x-input-label for="municipio_id" :value="__('Municipio')" />
                    <select wire:model="municipio_id" id="municipio_id" name="municipio_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona un municipio</option>
                        @foreach (\App\Models\Municipio::all() as $municipio)
                            <option value="{{ $municipio->id_municipio }}">{{ $municipio->nombre_municipio }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('municipio_id')" />
                </div>
                <div>
                    <x-input-label for="estatus" :value="__('Estatus')" />
                    <select wire:model="estatus" id="estatus"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('estatus')" />
                </div>
            </div>
        @endif


        {{-- 🔹 Campos extra SOLO si el usuario es ALUMNO --}}
        @if (auth()->user()->hasRole('alumno'))
            <div>
                <x-input-label for="matricula" :value="__('Matricula')" />
                <x-text-input wire:model="matricula" id="matricula" name="matricula" type="text"
                    class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('matricula')" />
            </div>

            <div>
                <x-input-label for="edad_alumno" :value="__('Edad')" />
                <x-text-input wire:model="edad_alumno" id="edad_alumno" name="edad_alumno" type="text"
                    class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('edad_alumno')" />
            </div>

            <div>
                <x-input-label for="carrera_alumno" :value="__('Carrera')" />
                <x-select wire:model="carrera_alumno" id="carrera_alumno" name="carrera_alumno" type="text"
                    class="mt-1 block w-full">
                    <option value='' disabled selected>Selecciona una carrera...</option>
                    @foreach (\App\Models\Carrera::all() as $carrera)
                        <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                    @endforeach
                </x-select>
                <x-input-error class="mt-2" :messages="$errors->get('carrera_alumno')" />
            </div>

            <div>
                <x-input-label for="telefono_alumno" :value="__('Telefono')" />
                <x-text-input wire:model="telefono_alumno" id="telefono_alumno" name="telefono_alumno"
                    type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('telefono_alumno')" />
            </div>

            <div>
                <x-input-label for="sexo_alumno" :value="__('Sexo')" />

                <x-select wire:model="sexo_alumno" id="sexo_alumno" name="sexo_alumno" type="text"
                    class="mt-1 block w-full">
                    <option value="" selected disabled>SELECCIONA UNA OPCION...</option>
                    <option value="M">MASCULINO</option>
                    <option value="F">FEMENINO</option>
                </x-select>
                <x-input-error class="mt-2" :messages="$errors->get('sexo_alumno')" />
            </div>

            <div>
                <x-input-label for="colonia_alumno" :value="__('Colonia')" />
                <x-text-input wire:model="colonia_alumno" id="colonia_alumno" name="colonia_alumno" type="text"
                    class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('colonia_alumno')" />
            </div>

            <div>
                <x-input-label for="municipio_alumno" :value="__('Municipio')" />
                <x-select wire:model="municipio_alumno" id="municipio_alumno" name="municipio_alumno" type="text"
                    class="mt-1 block w-full">
                    <option value='' disabled selected>Selecciona un municipio...</option>
                    @foreach (\App\Models\Municipio::all() as $municipio)
                        <option value="{{ $municipio->id_municipio }}">{{ $municipio->nombre_municipio }}</option>
                    @endforeach
                </x-select>
                <x-input-error class="mt-2" :messages="$errors->get('municipio_alumno')" />
            </div>
        @endif
        {{-- Botón guardar --}}
        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>
            <x-action-message class="me-3" on="profile-updated">
                {{ __('Guardado.') }}
            </x-action-message>
        </div>
    </form>
</section>
