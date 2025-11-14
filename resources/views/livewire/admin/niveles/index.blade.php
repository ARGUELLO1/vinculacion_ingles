<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Niveles
            </h2>

            @can('niveles.create')
                <x-primary-button :href="route('capturista.niveles.create')" wire:navigate>
                    + Crear Nivel
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Header con búsqueda y filtros -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-text-input wire:model.live="search" placeholder="Buscar niveles..."
                                class="w-full lg:w-64" />
                        </div>
                        <div class="flex flex-wrap gap-4 items-center">
                            <!-- Filtro por estado -->
                            <div class="flex items-center space-x-2">
                                <x-input-label for="filtroEstado" value="Estado:" class="text-sm whitespace-nowrap" />
                                <select wire:model.live="filtroEstado" id="filtroEstado"
                                    class="border-gray-300 rounded-md text-sm w-full md:w-auto">
                                    <option value="todos">Todos</option>
                                    <option value="activos">Activos</option>
                                    <option value="concluidos">Concluidos</option>
                                </select>
                            </div>

                            <!-- Select para items por página -->
                            <div class="flex items-center space-x-2">
                                <x-input-label for="perPage" value="Mostrar:" class="text-sm whitespace-nowrap" />
                                <select wire:model.live="perPage" id="perPage"
                                    class="border-gray-300 rounded-md text-sm w-full md:w-auto">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contador de resultados -->
                    @php
                        $totalNiveles = $niveles->total();
                        $nivelesActivos = $niveles->where('nivel_concluido', 0)->count();
                        $nivelesConcluidos = $niveles->where('nivel_concluido', 1)->count();
                    @endphp
                    <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="font-medium">Total:</span>
                            <span class="ml-1">{{ $totalNiveles }} nivel(es)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="font-medium text-green-600">Activos:</span>
                            <span class="ml-1">{{ $nivelesActivos }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                            <span class="font-medium text-gray-600">Concluidos:</span>
                            <span class="ml-1">{{ $nivelesConcluidos }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nivel - Grupo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Nombre del Profesor
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Periodo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Estado
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Parcial Activo
                                </th>
                                @can('niveles.options')
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($niveles as $nivel)
                                <tr class="hover:bg-gray-50 {{ $nivel->isConcluido() ? 'bg-gray-50' : '' }}">
                                    <!-- ID - Solo en desktop grande -->
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center hidden lg:table-cell">
                                        {{ $nivel->id_nivel }}
                                    </td>

                                    <!-- Nivel - Grupo - Siempre visible -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                        <div class="font-medium">
                                            {{ $nivel->nivel }} - {{ $nivel->nombre_grupo }}
                                        </div>
                                        <div class="text-xs text-gray-500 lg:hidden">
                                            {{ $nivel->profesor->nombre_completo ?? $nivel->profesor->nombre }}
                                        </div>
                                    </td>

                                    <!-- Profesor - Solo en desktop grande -->
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center hidden lg:table-cell">
                                        {{ $nivel->profesor->nombre_completo ?? $nivel->profesor->nombre }}
                                    </td>

                                    <!-- Periodo - Solo en desktop grande -->
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center hidden lg:table-cell">
                                        {{ $nivel->periodo->periodo }}
                                    </td>

                                    <!-- Estado - Siempre visible -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $nivel->isConcluido() ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $nivel->isConcluido() ? 'Concluido' : 'Activo' }}
                                        </span>
                                    </td>

                                    <!-- Parcial Activo - Siempre visible -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                        @if ($nivel->isConcluido())
                                            <span class="text-sm text-gray-500">Nivel concluido</span>
                                        @else
                                            <div class="flex flex-col space-y-2">
                                                <!-- Radio buttons para cambiar parcial -->
                                                <div class="flex justify-center space-x-4">
                                                    @foreach ([1, 2, 3] as $parcial)
                                                        <label class="flex items-center space-x-1 cursor-pointer">
                                                            <input type="radio" name="parcial_{{ $nivel->id_nivel }}"
                                                                value="{{ $parcial }}"
                                                                {{ $nivel->isParcialActivo($parcial) ? 'checked' : '' }}
                                                                wire:change="cambiarParcial({{ $nivel->id_nivel }}, {{ $parcial }})"
                                                                class="text-blue-600 focus:ring-blue-500">
                                                            <span
                                                                class="text-sm {{ $nivel->isParcialActivo($parcial) ? 'font-bold text-blue-600' : 'text-gray-600' }}">
                                                                P{{ $parcial }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <!-- Indicador visual del parcial activo -->
                                                <div class="text-xs text-gray-500">
                                                    Activo: {{ $nivel->nombre_parcial_activo }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Acciones - Siempre visible -->
                                    @can('niveles.options')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div
                                                class="flex flex-col sm:flex-row lg:flex space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-2 justify-center items-center">
                                                @if ($nivel->isConcluido())
                                                    <!-- Botones para nivel concluido -->
                                                    @can('niveles.reactivate')
                                                        <x-green-button wire:click="reactivarNivel({{ $nivel->id_nivel }})"
                                                            class="p-2" title="Reactivar Nivel">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                        </x-green-button>
                                                    @endcan
                                                @else
                                                    <!-- Botones para nivel activo -->
                                                    @can('niveles.conclude')
                                                        <x-secondary-button wire:click="concluirNivel({{ $nivel->id_nivel }})"
                                                            class="p-2" title="Concluir Nivel">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </x-secondary-button>
                                                    @endcan
                                                @endif

                                                @can('niveles.show')
                                                    <x-secondary-button
                                                        wire:click="$dispatch('openShowNivelModal', { nivelId: {{ $nivel->id_nivel }} })"
                                                        class="p-2" title="Ver">
                                                        <x-icons.eye />
                                                    </x-secondary-button>
                                                @endcan

                                                @can('niveles.update')
                                                    <x-primary-button :href="route('admin.niveles.edit', $nivel->id_nivel)" wire:navigate class="p-2"
                                                        title="Editar">
                                                        <x-icons.pencil />
                                                    </x-primary-button>
                                                @endcan

                                                @can('niveles.delete')
                                                    <x-danger-button
                                                        wire:click="$dispatch('openDeleteNivelModal', { nivelId: {{ $nivel->id_nivel }} })"
                                                        class="p-2" title="Borrar">
                                                        <x-icons.trash />
                                                    </x-danger-button>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <p class="text-lg font-medium mb-2">No se encontraron niveles</p>
                                            <p class="text-sm">
                                                @if ($filtroEstado !== 'todos' || $search)
                                                    No hay niveles que coincidan con los filtros aplicados.
                                                    <button wire:click="$set('search', '')"
                                                        class="text-blue-600 hover:text-blue-800 ml-1">
                                                        Limpiar filtros
                                                    </button>
                                                @else
                                                    No hay niveles registrados en el sistema.
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

    <livewire:modal-global.niveles.show-modal />
    <livewire:modal-global.niveles.delete-modal />

    <!-- Sistema de Notificaciones Automáticas -->
    @if (session('success'))
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
                        <div class="mt-1 text-sm text-gray-600">
                            {{ session('success') }}
                        </div>
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
                        <div class="mt-1 text-sm text-gray-600">
                            {{ session('error') }}
                        </div>
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
