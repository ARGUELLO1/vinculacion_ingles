<div>
    @if ($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity z-40"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Header -->
                    <div class="bg-white px-8 pt-8 pb-6 border-b border-gray-100">
                        <div class="text-center">
                            <div
                                class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $nivelGrupo ?? 'Nivel no disponible' }}
                            </h2>
                            <p class="text-gray-500">{{ $nombreAlumno ?? 'Alumno no disponible' }}</p>

                            <!-- Información del parcial activo -->
                            <div
                                class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Modificando Parcial {{ $parcialActivo }}
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="bg-white px-8 py-6">
                        <div class="space-y-6">
                            <!-- Información importante -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-blue-700 font-medium">Solo puedes modificar asistencias
                                            del Parcial {{ $parcialActivo }}</p>
                                        <p class="text-xs text-blue-600 mt-1">Las asistencias de otros parciales no
                                            están disponibles para modificación.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de Fecha -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Seleccionar Fecha *</label>
                                <select wire:model.live="fechaSeleccionada"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 text-base">
                                    <option value="">-- Selecciona una fecha --</option>
                                    @foreach ($fechasDisponibles as $fecha)
                                        <option value="{{ $fecha }}">
                                            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - Parcial
                                            {{ $parcialActivo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fechaSeleccionada')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror

                                @if ($fechasDisponibles->count() === 0)
                                    <div class="text-center py-4 bg-gray-50 rounded-lg">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">No hay asistencias registradas para el Parcial
                                            {{ $parcialActivo }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Estado de Asistencia -->
                            @if ($fechaSeleccionada)
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Estado de Asistencia
                                        *</label>
                                    <div class="space-y-2">
                                        @php
                                            $opcionesAsistencia = [
                                                'A' => 'Asistió',
                                                'F' => 'Falta',
                                            ];
                                        @endphp

                                        @foreach ($opcionesAsistencia as $valor => $label)
                                            <label
                                                class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-200 transition-colors">
                                                <input type="radio" wire:model="asistenciaSeleccionada"
                                                    value="{{ $valor }}"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('asistenciaSeleccionada')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @elseif($fechasDisponibles->count() > 0)
                                <div class="text-center py-6 bg-gray-50 rounded-xl">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Selecciona una fecha para gestionar la
                                        asistencia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-8 py-4 rounded-b-3xl space-y-3">
                        <x-green-button wire:click="guardarAsistencia" wire:loading.attr="disabled"
                            class="w-full justify-center py-3 text-base font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$fechaSeleccionada || !$asistenciaSeleccionada">
                            <span wire:loading.remove>Guardar Asistencia</span>
                            <span wire:loading>Guardando...</span>
                        </x-green-button>

                        <x-danger-button wire:click="closeModal"
                            class="w-full justify-center py-3 text-base font-medium">
                            Cancelar
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
