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
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Calificaciones del Alumno</h2>
                            <p class="text-lg font-semibold text-gray-700">{{ $nombreAlumno ?? 'Alumno no disponible' }}
                            </p>
                            <p class="text-gray-500 mt-1">{{ $nivelGrupo ?? 'Nivel no disponible' }}</p>
                        </div>
                    </div>

                    <!-- Contenido - Calificaciones -->
                    <div class="bg-white px-8 py-6">
                        <div class="space-y-6">
                            @if ($nota)
                                <!-- Tarjeta de Calificaciones -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Calificaciones
                                        Parciales</h3>

                                    <div class="grid grid-cols-3 gap-4 mb-4">
                                        <!-- Parcial 1 -->
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-gray-600 mb-1">Parcial 1</div>
                                            <div
                                                class="text-2xl font-bold {{ $nota->nota_parcial_1 >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $nota->nota_parcial_1 ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($nota->nota_parcial_1 >= 70)
                                                    <span class="text-green-600">✓ Aprobado</span>
                                                @else
                                                    <span class="text-red-600">✗ Reprobado</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Parcial 2 -->
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-gray-600 mb-1">Parcial 2</div>
                                            <div
                                                class="text-2xl font-bold {{ $nota->nota_parcial_2 >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $nota->nota_parcial_2 ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($nota->nota_parcial_2 >= 70)
                                                    <span class="text-green-600">✓ Aprobado</span>
                                                @else
                                                    <span class="text-red-600">✗ Reprobado</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Parcial 3 -->
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-gray-600 mb-1">Parcial 3</div>
                                            <div
                                                class="text-2xl font-bold {{ $nota->nota_parcial_3 >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $nota->nota_parcial_3 ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($nota->nota_parcial_3 >= 70)
                                                    <span class="text-green-600">✓ Aprobado</span>
                                                @else
                                                    <span class="text-red-600">✗ Reprobado</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Promedio -->
                                    <div class="border-t border-blue-200 pt-4 mt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-gray-800">Promedio Final</span>
                                            <span
                                                class="text-3xl font-bold {{ $nota->promedio >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($nota->promedio, 1) }}
                                            </span>
                                        </div>
                                        <div class="text-center mt-2">
                                            @if ($nota->promedio >= 70)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Aprobado
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Reprobado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Información de Evaluación</h4>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div class="text-gray-600">Calificación mínima:</div>
                                        <div class="font-medium">70 por parcial</div>
                                        <div class="text-gray-600">Promedio mínimo:</div>
                                        <div class="font-medium">70</div>
                                        <div class="text-gray-600">Estado:</div>
                                        <div
                                            class="font-medium {{ $nota->promedio >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $nota->promedio >= 70 ? 'Aprobado' : 'Reprobado' }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Sin calificaciones -->
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5zm0 0l9-5-9-5-9 5 9 5z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sin calificaciones registradas
                                    </h3>
                                    <p class="text-gray-500">Este alumno no tiene calificaciones registradas para este
                                        nivel.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-8 py-4 rounded-b-3xl">
                        <button wire:click="closeModal"
                            class="w-full justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
