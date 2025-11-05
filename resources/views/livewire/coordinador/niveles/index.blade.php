<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Niveles
            </h2>

            @can('niveles.create')
                <x-primary-button :href="route('coordinador.niveles.create')" wire:navigate>
                    + Crear Nivel
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Header con búsqueda -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-text-input wire:model.live="search" placeholder="Buscar niveles..."
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
                                    Modalidad
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
                                <tr class="hover:bg-gray-50">
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

                                    <!-- Modalidad - Siempre visible -->
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center hidden md:table-cell">
                                        {{ $nivel->modalidad->tipo_modalidad }}
                                    </td>

                                    <!-- Acciones - Siempre visible -->
                                    @can('niveles.options')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div
                                                class="flex flex-col sm:flex-row lg:flex space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-2 justify-center items-center">
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
                                                    <x-primary-button
                                                        wire:click="$dispatch('openDeleteNivelModal', { nivelId: {{ $nivel->id_nivel }} })"
                                                        class="p-2" title="Borrar">
                                                        <x-icons.trash />
                                                    </x-primary-button>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron niveles
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
