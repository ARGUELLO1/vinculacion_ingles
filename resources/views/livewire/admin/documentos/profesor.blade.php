<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Planeaciones de Profesores
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Header con búsqueda -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-text-input wire:model.live="search" placeholder="Buscar por profesor o nivel..."
                                class="w-full lg:w-64" />
                        </div>
                        <div class="flex items-center space-x-4">
                            <x-input-label for="perPage" value="Mostrar:" />
                            <select wire:model.live="perPage" id="perPage" class="border-gray-300 rounded-md">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre Profesor
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nivel - Grupo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Modalidad
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Planeación
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($niveles as $nivel)
                                @php
                                    $planeacion = $nivel->documentosProfesores->firstWhere('tipo_doc', 'planeacion');
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <!-- Nombre Profesor -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $nivel->profesor->nombre_completo ?? $nivel->profesor->nombre }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $nivel->profesor->id_profesor }}
                                        </div>
                                    </td>

                                    <!-- Nivel - Grupo -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $nivel->nivel }} - {{ $nivel->nombre_grupo }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Aula: {{ $nivel->aula }}
                                        </div>
                                    </td>

                                    <!-- Modalidad -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $nivel->modalidad->tipo_modalidad }}
                                    </td>

                                    <!-- Planeación -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($planeacion)
                                            <div class="flex flex-col items-center space-y-2">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Subido
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($planeacion->created_at)->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Opciones -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            @if ($planeacion)
                                                <!-- Ver documento -->
                                                <a href="{{ route('documentos.profesor.ver', $planeacion->id_documento_profesor) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150"
                                                    title="Ver Planeación">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                <!-- Eliminar documento -->
                                                <x-danger-button
                                                    wire:click="$dispatch('openDeleteDocumentoModal', { documentoId: {{ $planeacion->id_documento_profesor }} })"
                                                    class="p-2" title="Eliminar Planeación">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </x-danger-button>
                                            @else
                                                <!-- Subir documento -->
                                                <x-primary-button wire:click="abrirModalSubir({{ $nivel->id_nivel }})"
                                                    class="p-2" title="Subir Planeación">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                    </svg>
                                                </x-primary-button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <p class="text-lg font-medium mb-2">No se encontraron niveles</p>
                                            <p class="text-sm">
                                                @if ($search)
                                                    No hay niveles activos que coincidan con la búsqueda.
                                                    <button wire:click="$set('search', '')"
                                                        class="text-blue-600 hover:text-blue-800 ml-1">
                                                        Limpiar búsqueda
                                                    </button>
                                                @else
                                                    No hay niveles activos registrados en el sistema.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $niveles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal global para subir documento -->
    <livewire:modal-global.documentos.create-modal />

    <!-- Modal global para eliminar documento -->
    <livewire:modal-global.documentos.delete-modal />

    <!-- Sistema de Notificaciones Automáticas -->
    @if (session('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="rounded-lg border border-green-400 bg-white shadow-lg">
                <div class="flex items-center gap-3 bg-green-50 p-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 rounded-full bg-green-100 p-1.5 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-green-800">Éxito</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ session('message') }}
                        </p>
                    </div>

                    <!-- Close Button -->
                    <button @click="show = false"
                        class="flex-shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-4">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="rounded-lg border border-red-400 bg-white shadow-lg">
                <div class="flex items-center gap-3 bg-red-50 p-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 rounded-full bg-red-100 p-1.5 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-800">Error</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ session('error') }}
                        </p>
                    </div>

                    <!-- Close Button -->
                    <button @click="show = false"
                        class="flex-shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-4">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
