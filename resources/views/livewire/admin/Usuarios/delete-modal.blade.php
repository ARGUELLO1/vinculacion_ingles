<div>
    @if ($isOpen)
        <!-- Fondo overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>

        <!-- Modal centrado -->
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h2 class="text-lg font-medium text-gray-900">
                            ¿Estás seguro de eliminar este {{ $userType }}?
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Se eliminará: <strong>{{ $userName }}</strong>
                        </p>

                        <p class="mt-2 text-sm text-red-600">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-danger-button wire:click="deleteUser" class="w-full justify-center sm:ml-3 sm:w-auto">
                            Eliminar
                        </x-danger-button>

                        <x-secondary-button wire:click="closeModal"
                            class="mt-3 w-full justify-center sm:mt-0 sm:w-auto">
                            Cancelar
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
