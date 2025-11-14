<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Actas - {{ $nivelInfo['nivel_grupo'] ?? '' }}
            </h2>

            <x-danger-button :href="route('admin.documentos.alumnos')" wire:navigate>
                Volver
            </x-danger-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Información del Nivel -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-800">Nivel - Grupo</h3>
                            <p class="text-lg font-semibold text-blue-900">{{ $nivelInfo['nivel_grupo'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-green-800">Profesor</h3>
                            <p class="text-lg font-semibold text-green-900">{{ $nivelInfo['profesor'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-purple-800">Modalidad</h3>
                            <p class="text-lg font-semibold text-purple-900">{{ $nivelInfo['modalidad'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-orange-800">Total Actas</h3>
                            <p class="text-lg font-semibold text-orange-900">{{ $conteoActasNivel }}</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-800">Aula</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $nivelInfo['aula'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-indigo-800">Total Alumnos</h3>
                            <p class="text-lg font-semibold text-indigo-900">{{ $nivelInfo['total_alumnos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Panel de Acciones Globales -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Acciones Globales del Nivel</h3>
                            <p class="text-sm text-gray-600">Gestiona todas las actas del grupo</p>
                        </div>
                        <div class="flex space-x-3">
                            <!-- Botón Subir Constancias Globales -->
                            <x-primary-button wire:click="abrirModalSubir()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Subir Actas
                            </x-primary-button>

                            <!-- Botón Eliminar Todas (solo si hay constancias) -->
                            @if ($conteoActasNivel > 0)
                                <x-danger-button wire:click="abrirModalEliminarTodoNivel">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar Todas
                                </x-danger-button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Lista de Alumnos -->
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Alumnos del Grupo</h3>

                    @if (count($alumnos) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($alumnos as $alumno)
                                <div
                                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $alumno->nombre_completo }}</h4>
                                            <p class="text-sm text-gray-500">Matrícula: {{ $alumno->matricula }}</p>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $alumno->actas_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $alumno->actas_count }} actas(s)
                                        </span>
                                    </div>

                                    <div class="flex space-x-2">
                                        <!-- Botón Subir para este alumno -->
                                        <button wire:click="abrirModalSubir({{ $alumno->id_alumno }})"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            Subir
                                        </button>

                                        <!-- Botón Ver Detalle (solo si tiene constancias) -->
                                        @if ($alumno->actas_count > 0)
                                            <button wire:click="verActasAlumno({{ $alumno->id_alumno }})"
                                                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>

                                            <!-- Botón Eliminar Todas del alumno -->
                                            @if ($alumno->actas_count > 0)
                                                <button wire:click="abrirModalEliminarAlumno({{ $alumno->id_alumno }})"
                                                    class="inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    title="Eliminar todas las constancias">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <p class="mt-2 text-lg font-medium text-gray-900">No hay alumnos en este nivel</p>
                            <p class="text-gray-500">Los alumnos aparecerán aquí una vez que se inscriban al nivel.</p>
                        </div>
                    @endif
                </div>

                <!-- Detalle de Constancias del Alumno Seleccionado -->
                @if ($detalleAlumno && $alumnoDetalle)
                    <div class="border-t border-gray-200 p-6 bg-gray-50">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Actas de {{ $alumnoDetalle->nombre_completo }}
                            </h3>
                            <button wire:click="cerrarDetalleAlumno" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        @if ($actasAlumno && $actasAlumno->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Archivo
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fecha de Subida
                                            </th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($actasAlumno as $acta)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="text-sm font-medium text-gray-900">
                                                            Acta_{{ $loop->iteration }}.pdf
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($acta->created_at)->format('d/m/Y H:i') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                    <div class="flex justify-center space-x-2">
                                                        <!-- Ver Constancia -->
                                                        <a href="{{ route('documentos.alumno.ver', $acta->id_documento) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                            title="Ver Constancia">
                                                            <svg class="w-4 h-4 mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Ver
                                                        </a>

                                                        <!-- Eliminar Constancia -->
                                                        @if ($alumno->actas_count > 0)
                                                            <button
                                                                wire:click="abrirModalEliminarAlumno({{ $alumno->id_alumno }})"
                                                                class="inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                                title="Eliminar todas las constancias">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-lg font-medium text-gray-900">No hay actas para este alumno
                                </p>
                                <p class="text-gray-500">Utiliza el botón "Subir" para agregar actas.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- En la sección donde incluyes los modales, agrega: -->
    <livewire:modal-global.documentos.actas-modal />
    <livewire:modal-global.documentos.actas-alumno-modal />
    <livewire:modal-global.documentos.eliminar-actas-alumno-modal />
</div>
