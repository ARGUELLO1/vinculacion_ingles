<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Capturistas
            </h2>
            @can('capturistas.create')
                <x-primary-button :href="route('coordinador.capturistas.create')" wire:navigate>
                    + Crear Capturistas
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Header con búsqueda -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div class="flex-1">
                            <x-text-input wire:model.live="search" placeholder="Buscar capturistas..."
                                class="w-full md:w-64" />
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
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre Completo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell lg:table-cell">
                                    Created at
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Updated at
                                </th>
                                @can('capturistas.options')
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Options
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($capturistas as $user)
                                <tr>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        {{ $user->capturista?->id_capturista ?? 'N/A' }}
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        {{ $user->capturista->nombre_completo ?? $user->name }}
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        {{ $user->email }}
                                    </td>
                                    <td
                                        class="text-center px-6 py-4 whitespace-nowrap hidden md:table-cell lg:table-cell">
                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        {{ $user->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                    @can('capturistas.options')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 items-center justify-center">
                                                @can('capturistas.update')
                                                    <x-secondary-button :href="route('coordinador.capturistas.edit', $user->capturista)" wire:navigate>
                                                        Editar
                                                    </x-secondary-button>
                                                @endcan

                                                @can('capturistas.delete')
                                                    <x-danger-button wire:click="delete({{ $user->id }})"
                                                        wire:confirm="¿Estás seguro de eliminar este coordinador?">
                                                        Eliminar
                                                    </x-danger-button>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron Capturistas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $capturistas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistema de Notificaciones Automáticas -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto inset-0 z-50 flex items-center justify-center p-4">

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
                        <p class="mt-1 text-sm text-gray-600">
                            {{ session('success') }}
                        </p>
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
