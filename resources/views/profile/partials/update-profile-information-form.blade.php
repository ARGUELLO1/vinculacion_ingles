<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <!-- Mensaje aquí -->
        @if (session('status') === 'profile-incomplete' || session('status') === 'complete-profile')
            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Completa tu perfil
                        </h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p>
                                Necesitamos que completes la siguiente información para activar tu cuenta completamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        @endif
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Campos comunes para todos los usaurios -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $perfilData['nombre'])"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="ap_paterno" :value="__('Apellido Paterno')" />
            <x-text-input id="ap_paterno" name="ap_paterno" type="text" class="mt-1 block w-full" :value="old('ap_paterno', $perfilData['ap_paterno'] ?? '')"
                required autofocus autocomplete="ap_paterno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_paterno')" />
        </div>

        <div>
            <x-input-label for="ap_materno" :value="__('Apellido Materno')" />
            <x-text-input id="ap_materno" name="ap_materno" type="text" class="mt-1 block w-full" :value="old('ap_materno', $perfilData['ap_materno'] ?? '')"
                required autofocus autocomplete="ap_materno" />
            <x-input-error class="mt-2" :messages="$errors->get('ap_materno')" />
        </div>
        <!-- Fin de los campos comunes para todos los usaurios -->


        <!-- Campos específicos para ALUMNO -->
        @if (auth()->user()->hasRol('alumno'))
            <div>
                <x-input-label for="matricula" :value="__('Matrícula')" />
                <x-text-input id="matricula" name="matricula" type="text" class="mt-1 block w-full"
                    :value="old('matricula', $perfilData['matricula'] ?? '')" maxlength="10" />
                <x-input-error class="mt-2" :messages="$errors->get('matricula')" />
            </div>

            <div>
                <x-input-label for="edad" :value="__('Edad')" />
                <x-text-input id="edad" name="edad" type="number" class="mt-1 block w-full" :value="old('edad', $perfilData['edad'] ?? '')"
                    min="16" max="100" />
                <x-input-error class="mt-2" :messages="$errors->get('edad')" />
            </div>

            <div>
                <x-input-label for="telefono" :value="__('Teléfono')" />
                <x-text-input id="telefono" name="telefono" type="tel" class="mt-1 block w-full" :value="old('telefono', $perfilData['telefono'] ?? '')"
                    maxlength="15" />
                <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
            </div>

            <div>
                <x-input-label for="sexo" :value="__('Sexo')" />
                <select id="sexo" name="sexo"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleccionar</option>
                    <option value="M" {{ old('sexo', $perfilData['sexo'] ?? '') == 'M' ? 'selected' : '' }}>
                        Masculino</option>
                    <option value="F" {{ old('sexo', $perfilData['sexo'] ?? '') == 'F' ? 'selected' : '' }}>
                        Femenino</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
            </div>

            <div>
                <x-input-label for="carrera" :value="__('Carrera')" />
                <select id="carrera_id" name="carrera_id"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleccionar</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id_carrera }}"
                            {{ old('carrera_id', $perfilData->carrera_id ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                            {{ $carrera->nombre_carrera }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('carrera')" />
            </div>
        @endif
        <!-- Fin de los campos específicos para ALUMNO -->

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Gaurdar Cambios') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
