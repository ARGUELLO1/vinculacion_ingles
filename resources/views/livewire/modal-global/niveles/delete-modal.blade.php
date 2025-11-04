<div>
    @if ($isOpen)
        <!-- Fondo overlay con blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity z-40"></div>

        <!-- Modal centrado -->
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">

                    <!-- Icono de advertencia -->
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-4">
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>

                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                Confirmar Eliminación
                            </h2>

                            <!-- Información del usuario -->
                            <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                                <p class="text-sm font-medium text-gray-500 mb-1">Se eliminará el nivel y grupo:</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $nivelData }}</p>
                            </div>

                            <!-- Advertencia -->
                            <div
                                class="flex items-center justify-center space-x-2 text-red-600 bg-red-50 rounded-2xl p-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">Esta acción no se puede deshacer</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-6 rounded-b-3xl">
                        <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                            <!-- Botón Eliminar -->
                            <x-danger-button wire:click="deleteNivel"
                                class="w-full justify-center py-3 text-base font-medium rounded-xl border-0 bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-200">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Eliminar</span>
                                </div>
                            </x-danger-button>

                            <!-- Botón Cancelar -->
                            <x-secondary-button wire:click="closeModal"
                                class="w-full justify-center py-3 text-base font-medium rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Cancelar</span>
                                </div>
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
