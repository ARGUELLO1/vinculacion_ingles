<div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            <!-- Contenedor del modal -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <!-- Header del modal -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                    Eliminar Todas las Actas del Nivel
                                </h3>

                                <!-- Contenido de advertencia -->
                                <div class="bg-red-50 p-4 rounded-lg mt-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-red-800">¡Advertencia!</h3>
                                    </div>
                                    <p class="mt-2 text-sm text-red-600">
                                        Estás a punto de eliminar <strong>TODAS</strong> las constancias del nivel
                                        <strong>{{ $nivelInfo['nivel_grupo'] ?? '' }}</strong>.
                                        Esta acción no se puede deshacer y se perderán {{ $conteoConstanciasNivel }}
                                        archivo(s) de todos los alumnos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del modal -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-danger-button wire:click="eliminarTodasConstanciasNivel" wire:loading.attr="disabled"
                            class="w-full sm:w-auto sm:ml-3">
                            <span wire:loading.remove wire:target="eliminarTodasConstanciasNivel">Eliminar Todas</span>
                            <span wire:loading wire:target="eliminarTodasConstanciasNivel" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Eliminando...
                            </span>
                        </x-danger-button>
                        <x-secondary-button wire:click="cerrarModal" class="mt-3 w-full sm:mt-0 sm:w-auto">
                            Cancelar
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
