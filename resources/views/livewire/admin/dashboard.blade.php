<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Alerta para correo por defecto -->
    @if ($showEmailWarning)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-yellow-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm">
                            <strong>Correo por defecto detectado:</strong>
                            Por seguridad, actualiza tu correo electrónico en
                            <a href="{{ route('profile') }}"
                                class="font-medium underline text-yellow-700 hover:text-yellow-600">tu perfil</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Alerta para contraseña por defecto -->
    @if ($showPasswordWarning)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm">
                            <strong>¡Contraseña por defecto!</strong>
                            Por seguridad, cambia inmediatamente tu contraseña en
                            <a href="{{ route('profile') }}"
                                class="font-medium underline text-red-700 hover:text-red-600">tu perfil</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Alerta para nombre por defecto -->
    @if ($showNameWarning)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm">
                            <strong>Nombre genérico detectado:</strong>
                            Considera personalizar tu nombre en
                            <a href="{{ route('profile') }}"
                                class="font-medium underline text-blue-700 hover:text-blue-600">tu perfil</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Bienvenido al Panel del Administrador!') }}
                </div>
            </div>
        </div>
    </div>
</div>
