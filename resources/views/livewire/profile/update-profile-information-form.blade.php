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
                }
                break;

            case 'alumno':
                if ($user->alumno) {
                    $this->ap_paterno = $user->alumno->ap_paterno ?? '';
                    $this->ap_materno = $user->alumno->ap_materno ?? '';
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

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'ap_paterno' => ['required', 'string', 'max:255'],
            'ap_materno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        // Actualizar los datos en la tabla users (email y name)
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Actualizar los datos específicos en la tabla correspondiente según el rol
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
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="ap_paterno" :value="__('Apellido Paterno')" />
            <x-text-input wire:model="ap_paterno" id="ap_paterno" name="ap_paterno" type="text"
                class="mt-1 block w-full" required autofocus autocomplete="ap_paterno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_paterno')" />
        </div>

        <div>
            <x-input-label for="ap_materno" :value="__('Apellido Materno')" />
            <x-text-input wire:model="ap_materno" id="ap_materno" name="ap_materno" type="text"
                class="mt-1 block w-full" required autofocus autocomplete="ap_materno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_materno')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
