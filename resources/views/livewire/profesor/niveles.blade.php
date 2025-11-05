<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('NIVELES') }}
        </h2>
    </x-slot>
    <div class="min-h-screen bg-no-repeat bg-cover bg-center" style="background-image: url('/images/exp.jpg');">
        <div class="relative z-10 py-12 px-6">

            @if (!$nivelSeleccionado)

            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-bold text-black text-center mb-8">Selecciona un Nivel</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    {{-- Iteramos sobre los niveles que SÍ tiene el profesor --}}
                    @forelse ($nivelesDisponibles as $nivel)
                    <div
                        wire:click="verNivel({{ $nivel->nivel }})"
                        class="bg-white/90 backdrop-blur-md rounded-xl shadow-lg p-6 border border-white/30 
                                       transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 cursor-pointer"
                        wire:key="nivel-{{ $nivel->nivel }}">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                Nivel {{ $nivel->nivel }}
                            </span>
                            <span class="text-sm font-semibold text-gray-600">
                                {{ $nivel->grupos_count }} {{ Str::plural('Grupo', $nivel->grupos_count) }}
                            </span>
                        </div>
                        <h3 class="text-black text-2xl font-bold mb-3">
                            Grupos Disponibles
                        </h3>
                        <p class="text-gray-600 text-base mb-6">
                            Haz clic para ver los grupos que tienes asignados en este nivel.
                        </p>
                        <div class="text-right">
                            <button
                                class="px-6 py-2 text-white font-semibold rounded-lg bg-gray-800 hover:bg-gray-900 transition shadow-md">
                                Ver Grupos &rarr;
                            </button>
                        </div>
                    </div>
                    @empty
                    {{-- Mensaje si el profesor no tiene ningún grupo asignado --}}
                    <div class="sm:col-span-2 lg:col-span-3 bg-white/90 rounded-lg shadow-lg p-10 text-center border border-gray-300">
                        <h3 class="text-xl font-semibold text-gray-700">
                            Aún no tienes grupos asignados.
                        </h3>
                        <p class="text-gray-500 mt-2">
                            Contacta a coordinación para que te asignen tus grupos.
                        </p>
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

                {{-- Cabecera del Nivel (con el nuevo diseño) --}}
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start">
                        {{-- Título del Nivel --}}
                        <div class="mb-4 sm:mb-0">
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                                Nivel {{ $nivelSeleccionado }}
                            </h2>
                            <div class="flex items-center text-gray-500 text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-2.13l-2.114-2.115a2 2 0 01-.586-1.415V12a3 3 0 00-3-3H7a3 3 0 00-3 3v2.343a2 2 0 01-.586 1.415L2.13 15.87A3 3 0 007 18v2h5m0-12H9a1 1 0 100 2h6a1 1 0 100-2z" />
                                </svg>
                                <span>{{ $alumnosCount }} alumnos en total</span>
                            </div>
                        </div>
                        {{-- Info de Grupos --}}
                        <div class="bg-blue-100 text-blue-700 font-semibold px-4 py-2 rounded-full text-lg">
                            {{ $grupos->count() }} {{ Str::plural('grupo', $grupos->count()) }}
                        </div>
                    </div>
                </div>

                {{-- Grid de Grupos --}}
                @if (count($grupos) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    {{-- Loop de las nuevas tarjetas de grupo --}}
                    @foreach ($grupos as $grupo)
                    <div class="bg-white rounded-xl shadow-lg flex flex-col justify-between" wire:key="{{ $grupo->id_nivel }}">

                        {{-- Contenido principal de la tarjeta --}}
                        <div class="p-6">
                            {{-- Contenedor interior redondeado --}}
                            <div class="border border-gray-200 rounded-lg p-5">

                                {{-- Encabezado de la tarjeta (Nombre y Cupo) --}}
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
                                    Semestral / Presencial
                                </p>

                                {{-- Detalles (Aula y Horario) --}}
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

                                {{-- Enlace "Ver grupo" --}}
                                <div class="text-right mt-6">
                                    <a href="{{ route('profesor.grupo.vista', $grupo->id_nivel) }}"
                                        wire:navigate
                                        class="text-blue-600 font-semibold text-base hover:text-blue-800 transition">
                                        Ver grupo >
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Footer de la tarjeta (Periodo) --}}
                        <div class="bg-gray-50 border-t border-gray-100 rounded-b-xl px-6 py-4">
                            <p class="text-gray-500 text-sm">
                                Periodo: {{ $grupo->periodo->nombre_periodo ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Mensaje si no hay grupos --}}
                <div class="bg-white/90 rounded-lg shadow-lg p-10 text-center border border-gray-300">
                    <h3 class="text-xl font-semibold text-gray-700">
                        No tienes grupos registrados para este nivel.
                    </h3>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>