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
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
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
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                    @if ($alumnoSeleccionado)
                                        Subir Constancias para
                                        {{ \App\Models\Alumno::find($alumnoSeleccionado)->nombre_completo ?? 'Alumno' }}
                                    @else
                                        Subir Constancias Globales
                                    @endif
                                </h3>

                                <!-- Información del destino -->
                                <div class="bg-blue-50 p-4 rounded-lg mt-4">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Destino</h4>
                                    <p class="text-sm text-blue-900">
                                        @if ($alumnoSeleccionado)
                                            {{ $nivelInfo['nivel_grupo'] ?? '' }} -
                                            {{ \App\Models\Alumno::find($alumnoSeleccionado)->nombre_completo ?? 'Alumno' }}
                                        @else
                                            {{ $nivelInfo['nivel_grupo'] ?? '' }} - Constancias Globales
                                        @endif
                                    </p>
                                </div>

                                <!-- Input para múltiples archivos -->
                                <div class="mt-6">
                                    <x-input-label for="files"
                                        value="Seleccionar archivos PDF (Máx. 10MB cada uno)" />
                                    <input type="file" wire:model="form.files" id="files" multiple accept=".pdf"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />

                                    @error('form.files')
                                        <x-input-error class="mt-2" :messages="$message" />
                                    @enderror
                                    @error('form.files.*')
                                        <x-input-error class="mt-2" :messages="$message" />
                                    @enderror

                                    <!-- Vista previa de archivos seleccionados -->
                                    @if ($form->files)
                                        <div class="mt-4 space-y-2">
                                            <p class="text-sm font-medium text-gray-700">Archivos seleccionados:</p>
                                            @foreach ($form->files as $index => $file)
                                                <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                                    <span
                                                        class="text-sm text-green-800">{{ $file->getClientOriginalName() }}</span>
                                                    <span class="text-xs text-green-600">
                                                        {{ number_format($file->getSize() / 1024, 2) }} KB
                                                    </span>
                                                </div>
                                            @endforeach
                                            <p class="text-xs text-gray-500 mt-2">
                                                Total: {{ count($form->files) }} archivo(s) seleccionado(s)
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del modal -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-primary-button wire:click="subirConstancias" wire:loading.attr="disabled"
                            class="w-full sm:w-auto sm:ml-3" :disabled="empty($form->files)">
                            <span wire:loading.remove wire:target="subirConstancias">Subir Constancias</span>
                            <span wire:loading wire:target="subirConstancias" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Subiendo...
                            </span>
                        </x-primary-button>
                        <x-secondary-button wire:click="cerrarModal" class="mt-3 w-full sm:mt-0 sm:w-auto">
                            Cancelar
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
