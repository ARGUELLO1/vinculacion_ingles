<div>
    @if ($isOpen)
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>

        <!-- Modal -->
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Panel del modal -->
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <!-- Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                    Eliminar Planeación
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        ¿Estás seguro de que deseas eliminar esta planeación? Esta acción no se puede
                                        deshacer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="bg-white px-4 py-3 sm:px-6">
                        @if ($documento)
                            <!-- Información del documento -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-gray-800 mb-2">Información del documento:</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span class="font-medium">Nombre:</span>
                                        <span>{{ $documento->nombre_archivo }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Tipo:</span>
                                        <span
                                            class="capitalize">{{ str_replace('_', ' ', $documento->tipo_documento) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Subido:</span>
                                        <span>{{ $documento->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Tamaño:</span>
                                        <span>{{ number_format($documento->tamanio / 1024, 2) }} KB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Advertencia -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="text-sm text-red-700">
                                        El archivo será eliminado permanentemente del sistema.
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- Estado de error -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <svg class="h-8 w-8 text-yellow-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-sm text-yellow-700">No se pudo cargar la información del documento.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer con botones -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <!-- Botón Eliminar -->
                        <button type="button" wire:click="deleteDocumento" wire:loading.attr="disabled"
                            wire:target="deleteDocumento"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="deleteDocumento">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </span>
                            <span wire:loading wire:target="deleteDocumento">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Eliminando...
                            </span>
                        </button>

                        <!-- Botón Cancelar -->
                        <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                            wire:target="deleteDocumento"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Script para cerrar con ESC -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Cerrar modal con tecla ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && @this.isOpen) {
                    @this.closeModal();
                }
            });

            // Cerrar modal haciendo click fuera
            document.addEventListener('click', (e) => {
                if (@this.isOpen && e.target.classList.contains('fixed')) {
                    @this.closeModal();
                }
            });
        });
    </script>
</div>
