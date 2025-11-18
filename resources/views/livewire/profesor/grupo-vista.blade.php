<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            GRUPO - {{ $grupo->nombre_grupo }} - SELECCIONADO
        </h2>
    </x-slot>
    {{-- BLOQUE DE ALERTAS (Toast Notifications) --}}
    {{-- ¡Actualizado para manejar 'success' y 'error'! --}}
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:alerta-exito.window="message = $event.detail.mensaje; type = 'success'; show = true; setTimeout(() => show = false, 3000)"
        x-on:alerta-error.window="message = $event.detail.mensaje; type = 'error'; show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        style="display: none;"
        class="fixed top-5 right-5 text-white py-2 px-4 rounded-lg shadow-md z-50"
        {{-- Cambia de color según el tipo de alerta --}}
        :class="{ 'bg-green-600': type === 'success', 'bg-red-600': type === 'error' }">
        <span x-text="message"></span>
    </div>
    {{-- FIN: BLOQUE DE ALERTAS --}}


    <div class="min-h-screen bg-no-repeat bg-cover bg-center" style="background-image: url('/images/exp.jpg');">

        <div class="relative z-10 py-6 px-6">

            {{-- Título de la página y Mensaje de Parcial Activo --}}
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3">
                <h2 class="text-3xl font-bold text-black text-center"> {{-- Respetando tu 'text-black' --}}
                    Administrando Grupo: {{ $grupo->nombre_grupo }}
                </h2>
                <p class="text-black font-bold text-center text-lg"> {{-- Respetando tu 'text-black' --}}
                    (Nivel {{ $grupo->id_nivel }} | Aula: {{ $grupo->aula }})
                </p>

                {{-- Mensaje de Parcial Activo --}}
                @if ($parcialActivo && $grupo->nivel_concluido == 0)

                <div class="text-center">
                    <p class="text-yellow-300 bg-black/60 text-lg font-semibold mt-2 px-4 py-2 rounded-lg shadow-md inline-block">
                        Parcial Activo:
                        @switch($parcialActivoNumero)
                        @case(1)
                        Primer Parcial
                        @break
                        @case(2)
                        Segundo Parcial
                        @break
                        @case(3)
                        Tercer Parcial
                        @break
                        @default
                        No definido
                        @endswitch
                    </p>
                </div>


                @else
                <p class="text-red-400 text-center text-lg font-semibold mt-2">
                    No hay ningún parcial activo en este momento.
                </p>
                @endif
            </div>

            {{-- Contenido principal --}}
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if ($grupo->nivel_concluido == 0)
                {{-- 1. BOTONES PARA CAMBIAR DE MODO (NUEVO) --}}
                <div class="mb-3 flex justify-center rounded-lg shadow-sm" role="group">
                    <button type="button"
                        wire:click="setModo('calificaciones')"
                        class="py-2 px-4 text-sm font-medium rounded-l-lg border transition
                                   {{ $modo === 'calificaciones' 
                                        ? 'bg-gray-800 text-white border-gray-800' 
                                        : 'bg-white/90 text-gray-900 border-gray-300 hover:bg-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block -mt-1 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 13a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM8 11a1 1 0 100-2 1 1 0 000 2zM11 11a1 1 0 100-2 1 1 0 000 2zM14 11a1 1 0 100-2 1 1 0 000 2zM6 16a1 1 0 100-2 1 1 0 000 2zM9 16a1 1 0 100-2 1 1 0 000 2zM12 16a1 1 0 100-2 1 1 0 000 2zM3 6a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                        </svg>
                        Calificaciones
                    </button>
                    <button type="button"
                        wire:click="setModo('asistencia')"
                        class="py-2 px-4 text-sm font-medium rounded-r-lg border transition
                                   {{ $modo === 'asistencia' 
                                        ? 'bg-gray-800 text-white border-gray-800' 
                                        : 'bg-white/90 text-gray-900 border-gray-300 hover:bg-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block -mt-1 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a1 1 0 000 2h1v11a1 1 0 001 1h8a1 1 0 001-1V6h1a1 1 0 100-2h-1V3a1 1 0 00-1-1H6zM8 6h4v11H8V6z" clip-rule="evenodd" />
                        </svg>
                        Asistencia
                    </button>
                </div>
                @endif
                {{-- FIN: BOTONES DE MODO --}}

                {{-- 2. BOTONES DE DESCARGA DE EXCEL --}}
                <div class="mb-3 flex flex-wrap justify-center gap-2" role="group">

                    <span class="text-sm font-semibold text-black bg-white/80 px-2 py-1 rounded">
                        Descargar Listas de Asistencia:
                    </span>

                    <a href="{{ route('exportar.asistencias', ['grupo' => $grupo->id_nivel, 'parcial' => 1]) }}"
                        target="_blank" {{-- target="_blank" evita que Livewire se rompa --}}
                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-lg border
              bg-green-700 text-white border-green-700 hover:bg-green-800 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Parcial 1
                    </a>

                    <a href="{{ route('exportar.asistencias', ['grupo' => $grupo->id_nivel, 'parcial' => 2]) }}"
                        target="_blank"
                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-lg border
              bg-green-700 text-white border-green-700 hover:bg-green-800 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Parcial 2
                    </a>

                    <a href="{{ route('exportar.asistencias', ['grupo' => $grupo->id_nivel, 'parcial' => 3]) }}"
                        target="_blank"
                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-lg border
              bg-green-700 text-white border-green-700 hover:bg-green-800 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Parcial 3
                    </a>

                </div>
                {{-- FIN: BOTONES DE DESCARGA --}}

                {{-- PDF --}}
                @if ($grupo->nivel_concluido == 0)
                <div class="mb-3 flex flex-wrap justify-center gap-2" role="group">
                    <a href="{{ route('exportar.reporte', ['grupo' => $grupo->id_nivel, 'parcial' => $parcialActivoNumero]) }}"
                        target="_blank"
                        {{-- CLASES CAMBIADAS: más pequeño (px-2 py-0.5 text-xs) y sin margen (ml-3) --}}
                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg border
                       bg-red-700 text-white border-red-700 hover:bg-red-800 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        {{-- TEXTO CAMBIADO --}}
                        PDF (Parcial Activo)
                    </a>

                    <button type="button" wire:click="descargarPlaneacion"
                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-lg border
                       bg-blue-700 text-white border-blue-700 hover:bg-blue-800 transition ml-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Descargar Planeación
                    </button>
                    </div>
                    @endif

                    @if ($modo === 'calificaciones')

                    {{-- Controles de Búsqueda y Filtro --}}
                    <div class="flex flex-col sm:flex-row justify-between mb-3 space-y-2 sm:space-y-0">
                        <!-- Botón de Filtro N/A -->
                        <button
                            wire:click="toggleFiltroReprobados"
                            class="px-4 py-2 rounded-lg font-semibold transition
                                   {{ $filtroReprobados 
                                        ? 'bg-red-600 hover:bg-red-700 text-white' 
                                        : 'bg-white/90 hover:bg-white text-gray-800' }}"
                            @disabled(!$parcialActivo)>
                            @if ($filtroReprobados)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Mostrar Todos
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Mostrar Reprobados (N/A)
                            @endif
                        </button>

                        <!-- Buscador -->
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="search"
                            placeholder="Buscar por nombre o apellidos..."
                            class="w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    {{-- FIN: Controles --}}

                    {{-- Tabla de Calificaciones --}}
                    <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-lg overflow-hidden border border-white/30">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-800/80">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-white">Nombre Completo</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Sexo</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Carrera</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white">Parcial 1</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white">Parcial 2</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white">Parcial 3</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white/80">
                                    @forelse ($alumnos as $alumno)
                                    <tr wire:key="cal-{{ $alumno->id_alumno }}"> {{-- Llave única para el modo 'calificaciones' --}}
                                        <td class="whitespace-nowrap py-4 px-4 text-sm font-medium text-gray-900">
                                            {{ $alumno->ap_paterno }} {{ $alumno->ap_materno }} {{ $alumno->nombre }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $alumno->sexo }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $alumno->carrera->nombre_carrera ?? 'N/A' }}</td>

                                        {{-- Inputs con Lógica 'disabled' --}}
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                            <input type="number" step="0.1" min="0" max="99.9"
                                                wire:model.blur="calificaciones.{{ $alumno->id_alumno }}.nota_parcial_1"
                                                class="w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                                           disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                @disabled($parcialActivo !=='nota_parcial_1' || $grupo->nivel_concluido == 1)
                                            aria-label="Calificación Parcial 1 para {{ $alumno->nombre }}">
                                            @error('calificaciones.'.$alumno->id_alumno.'.nota_parcial_1') <span class="text-red-500 text-xs">Error</span> @enderror
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                            <input type="number" step="0.1" min="0" max="99.9"
                                                wire:model.blur="calificaciones.{{ $alumno->id_alumno }}.nota_parcial_2"
                                                class="w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                           disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                @disabled($parcialActivo !=='nota_parcial_2' || $grupo->nivel_concluido == 1)
                                            aria-label="Calificación Parcial 2 para {{ $alumno->nombre }}">
                                            @error('calificaciones.'.$alumno->id_alumno.'.nota_parcial_2') <span class="text-red-500 text-xs">Error</span> @enderror
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                            <input type="number" step="0.1" min="0" max="99.9"
                                                wire:model.blur="calificaciones.{{ $alumno->id_alumno }}.nota_parcial_3"
                                                class="w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                           disabled:bg-gray-200 disabled:cursor-not-allowed"
                                                @disabled($parcialActivo !=='' )
                                                @disabled($parcialActivo !=='nota_parcial_3' || $grupo->nivel_concluido == 1)
                                            aria-label="Calificación Parcial 3 para {{ $alumno->nombre }}">
                                            @error('calificaciones.'.$alumno->id_alumno.'.nota_parcial_3') <span class="text-red-500 text-xs">Error</span> @enderror
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-6 px-4 text-center text-gray-700">
                                            @if ($search)
                                            No se encontraron alumnos que coincidan con "{{ $search }}".
                                            @elseif($filtroReprobados)
                                            No hay alumnos con calificación N/A (menor a 70) en este parcial.
                                            @else
                                            Este grupo aún no tiene alumnos asignados.
                                            @endif
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 3. ENLACES DE PAGINACIÓN (NUEVO) --}}
                    <div class="mt-4">
                        {{ $alumnos->links() }}
                    </div>

                    {{-- FIN: VISTA DE CALIFICACIONES  --}}


                    {{-- 4. VISTA DE ASISTENCIA (NUEVA) --}}

                    @elseif ($modo === 'asistencia' && $grupo->nivel_concluido == 0 )

                    {{-- Controles de Asistencia (Selector de Fecha y Buscador) --}}
                    <div class="flex flex-col sm:flex-row justify-between mb-4 space-y-2 sm:space-y-0">
                        {{-- Selector de Fecha --}}
                        <div class="flex justify-center">
                            <label for="fecha-asistencia" class="text-black font-medium p-2">Fecha:</label>
                            <input type="date" id="fecha-asistencia"
                                wire:model.live="fechaAsistencia"
                                class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Buscador -->
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="search"
                            placeholder="Buscar por nombre o apellidos..."
                            class="w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Advertencia si no hay parcial activo --}}
                    @if ($parcialActivoNumero === 0)
                    <div class="bg-red-200/90 border-l-4 border-red-700 text-red-900 p-4 rounded-lg" role="alert">
                        <p class="font-bold">No se puede registrar asistencia</p>
                        <p>No hay ningún parcial activo configurado para este grupo.</p>
                    </div>
                    @else
                    {{-- Tabla de Asistencia --}}
                    <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-lg overflow-hidden border border-white/30">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-800/80">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-white">Nombre Completo</th>
                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white">Asistencia</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white/80">
                                    @forelse ($alumnos as $alumno)
                                    @php
                                    // Busca el estatus guardado para este alumno en este día
                                    $estatusActual = $asistenciasHoy[$alumno->id_alumno] ?? null;
                                    @endphp
                                    <tr wire:key="asis-{{ $alumno->id_alumno }}"> {{-- Llave única para el modo 'asistencia' --}}
                                        <td class="whitespace-nowrap py-4 px-4 text-sm font-medium text-gray-900">
                                            {{ $alumno->ap_paterno }} {{ $alumno->ap_materno }} {{ $alumno->nombre }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center space-x-2">

                                            {{-- Botón Presente (A) --}}
                                            <button
                                                wire:click="guardarAsistencia({{ $alumno->id_alumno }}, 'A')"
                                                class="px-3 py-1 text-sm font-semibold rounded-full transition
                                                               {{ $estatusActual === 'A' 
                                                                    ? 'bg-green-600 text-white shadow' 
                                                                    : 'bg-gray-200 text-gray-700 hover:bg-green-200' }}">
                                                Presente
                                            </button>

                                            {{-- Botón Falta (F) --}}
                                            <button
                                                wire:click="guardarAsistencia({{ $alumno->id_alumno }}, 'F')"
                                                class="px-3 py-1 text-sm font-semibold rounded-full transition
                                                               {{ $estatusActual === 'F' 
                                                                    ? 'bg-red-600 text-white shadow' 
                                                                    : 'bg-gray-200 text-gray-700 hover:bg-red-200' }}">
                                                Falta
                                            </button>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="py-6 px-4 text-center text-gray-700">
                                            @if ($search)
                                            No se encontraron alumnos que coincidan con "{{ $search }}".
                                            @else
                                            Este grupo aún no tiene alumnos asignados.
                                            @endif
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 5. ENLACES DE PAGINACIÓN (NUEVO) --}}
                    <div class="mt-4">
                        {{ $alumnos->links() }}
                    </div>
                    @endif

                    @endif
                    {{-- FIN: VISTA DE ASISTENCIA  --}}

                </div>
            </div>
        </div>
    </div>