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
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $nivelGrupo }}</h2>
                            <p class="text-gray-500">Información detallada del nivel</p>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="bg-white px-8 py-6">
                        <div class="space-y-6">
                            @php
                                $items = [
                                    [
                                        'icon' =>
                                            'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                        'label' => 'Aula',
                                        'value' => $aula,
                                        'color' => 'text-blue-600',
                                    ],
                                    [
                                        'icon' =>
                                            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                        'label' => 'Cupo Máximo',
                                        'value' => $cupoMax . ' estudiantes',
                                        'color' => 'text-green-600',
                                    ],
                                    [
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'label' => 'Horario',
                                        'value' => $horario,
                                        'color' => 'text-purple-600',
                                    ],
                                    [
                                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                        'label' => 'Profesor',
                                        'value' => $nombreProfesor,
                                        'color' => 'text-orange-600',
                                    ],
                                    [
                                        'icon' =>
                                            'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                        'label' => 'Periodo',
                                        'value' => $periodo,
                                        'color' => 'text-red-600',
                                    ],
                                    [
                                        'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9',
                                        'label' => 'Modalidad',
                                        'value' => $modalidad,
                                        'color' => 'text-indigo-600',
                                    ],
                                ];
                            @endphp

                            @foreach ($items as $item)
                                <div
                                    class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 {{ $item['color'] }}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $item['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-500">{{ $item['label'] }}</p>
                                        <p class="text-base font-semibold text-gray-900 truncate">{{ $item['value'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-8 py-4 rounded-b-3xl">
                        <x-secondary-button wire:click="closeModal"
                            class="w-full justify-center py-3 text-base font-medium rounded-xl border-0 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 hover:from-gray-200 hover:to-gray-300 transition-all shadow-sm">
                            Cerrar Vista
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
