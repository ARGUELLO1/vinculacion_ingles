<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('NIVELES') }}
        </h2>
    </x-slot>
    <div class="min-h-screen bg-no-repeat bg-cover bg-center" style="background-image: url('/images/exp.jpg');">
        <div class="relative z-10 py-12 px-6">


            <div class="max-w-7xl mx-auto mb-6">
                <div class="flex justify-center rounded-lg shadow-sm" role="group">
                    <button type="button"
                        wire:click="setVista('activos')"
                        class="py-2 px-6 text-sm font-medium rounded-l-lg border transition
                                   {{ $vista === 'activos' 
                                        ? 'bg-gray-800 text-white border-gray-800 z-10' 
                                        : 'bg-white/90 text-gray-900 border-gray-300 hover:bg-white' }}">
                        Grupos Activos
                    </button>
                    <button type="button"
                        wire:click="setVista('concluidos')"
                        class="py-2 px-6 text-sm font-medium rounded-r-lg border transition
                                   {{ $vista === 'concluidos' 
                                        ? 'bg-gray-800 text-white border-gray-800 z-10' 
                                        : 'bg-white/90 text-gray-900 border-gray-300 hover:bg-white' }}">
                        Historial (Concluidos)
                    </button>
                </div>
            </div>

            @if (!$nivelSeleccionado)
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-black">
                        {{ $vista === 'activos' ? 'Selecciona un Nivel' : 'Historial de Grupos Concluidos' }}
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


                    @forelse ($nivelesDisponibles as $nivel)

                    <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 border border-white/30 
                                        flex flex-col justify-between transform hover:scale-105 hover:shadow-xl transition-all duration-300">

                        <div class="flex justify-between items-center mb-4">
                            <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                                Nivel {{ $nivel->nivel }}
                            </span>
                            <span class="text-gray-500 text-sm font-medium">
                                {{ $nivel->grupos_count }} {{ Str::plural('Grupo', $nivel->grupos_count) }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-black text-2xl font-bold mb-2">
                                Grupos Disponibles
                            </h3>
                            <p class="text-gray-600 text-base">
                                Haz clic para ver los grupos que tienes asignados en este nivel.
                            </p>
                        </div>

                        <div class="mt-auto">
                            <button wire:click="verNivel({{ $nivel->nivel }})"
                                class="w-full px-6 py-3 text-white font-semibold rounded-lg bg-gray-800 hover:bg-gray-900 transition shadow-lg">
                                Ver Grupos &rarr;
                            </button>
                        </div>
                    </div>

                    @empty
                    {{-- Mensaje si no hay niveles --}}
                    <div class="sm:col-span-2 lg:col-span-3">
                        <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-lg p-10 text-center border border-gray-300">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                @if ($vista === 'activos')
                                Aún no tienes grupos activos asignados.
                                @else
                                No se encontró historial de grupos concluidos.
                                @endif
                            </h3>
                            <p class="text-gray-600">
                                {{ $vista === 'activos' ? 'Contacta a coordinación para que te asignen tus grupos.' : 'Cuando finalices un grupo, aparecerá aquí.' }}
                            </p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            @else

            <div class="max-w-7xl mx-auto">

                {{-- Botón para regresar --}}
                <div class="mb-6">
                    <button wire:click="regresar"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition shadow-lg">
                        ← Volver a niveles
                    </button>
                </div>

                {{-- Cabecera del Nivel --}}
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                                Nivel {{ $nivelSeleccionado }}
                            </h2>
                            <div class="flex items-center text-gray-500 text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-2.13l-2.114-2.115a2 2 0 01-.586-1.415V12a3 3 0 00-3-3H7a3 3 0 00-3 3v2.343a2 2 0 01-.586 1.415L2.13 15.87A3 3 0 007 18v2h5m0-12H9a1 1 0 100 2h6a1 1 0 100-2z" />
                                </svg>
                                <span>{{ $alumnosCount }} alumnos en total ({{ $vista === 'activos' ? 'Activos' : 'Historial' }})</span>
                            </div>
                        </div>
                        <div class="bg-blue-100 text-blue-700 font-semibold px-4 py-2 rounded-full text-lg">
                            {{ $grupos->count() }} {{ Str::plural('grupo', $grupos->count()) }}
                        </div>
                    </div>
                </div>

                {{-- Grid de Grupos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @forelse ($grupos as $grupo)
                    <div class="bg-white rounded-xl shadow-lg flex flex-col justify-between" wire:key="{{ $grupo->id_nivel }}">

                        <div class="p-6">
                            <div class="border border-gray-200 rounded-lg p-5">

                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-2xl font-bold text-gray-900">
                                        {{ $grupo->nombre_grupo }}
                                    </h3>
                                    <span class="text-sm font-semibold 
                                                         {{ $grupo->alumnos_count >= $grupo->cupo_max ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} 
                                                         px-3 py-1 rounded-full">
                                        {{ $grupo->alumnos_count }}/{{ $grupo->cupo_max }}
                                    </span>
                                </div>
                                <p class="text-gray-500 text-base mb-5">
                                    {{-- Mostramos Periodo Y Modalidad con las columnas correctas --}}
                                    {{ $grupo->modalidad->tipo_modalidad ?? 'Modalidad N/A' }}
                                </p>

                                <div class="space-y-3">
                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        <span>Aula: {{ $grupo->aula }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $grupo->horario }}</span>
                                    </div>
                                </div>

                                <div class="text-right mt-6">
                                    <a href="{{ route('profesor.grupo.vista', $grupo->id_nivel) }}"
                                        wire:navigate
                                        class="text-blue-600 font-semibold text-base hover:text-blue-800 transition">
                                        Ver grupo >
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 border-t border-gray-100 rounded-b-xl px-6 py-4">
                            <p class="text-gray-500 text-sm">
                                Periodo: {{ $grupo->periodo->periodo ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-2 lg:col-span-3">
                        <div class="bg-white/90 rounded-lg shadow-lg p-10 text-center border border-gray-300">
                            <h3 class="text-xl font-semibold text-gray-700">
                                @if ($vista === 'activos')
                                No tienes grupos activos registrados para este nivel.
                                @else
                                No hay grupos concluidos en el historial de este nivel.
                                @endif
                            </h3>
                        </div>
                    </div>
                    @endforelse
                </div>

            </div>
            @endif
        </div>
    </div>
</div>