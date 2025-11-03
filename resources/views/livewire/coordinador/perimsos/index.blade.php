<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Permisos
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Header con búsqueda -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div class="flex-1">
                            <x-text-input wire:model.live="search" placeholder="Buscar usuarios..."
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
                                    No.
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre Completo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rol
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Permisos
                                </th>
                                @can('permisos.create')
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($capturistas as $index => $usuario)
                                @php
                                    $rol = $usuario->roles->first()->name ?? 'Sin rol';
                                    $esCoordinador = $rol === 'coordinador';
                                    $esCapturista = $rol === 'capturista';

                                    // Obtener datos del perfil según el rol
                                    $perfil = $esCoordinador
                                        ? $usuario->coordinador
                                        : ($esCapturista
                                            ? $usuario->capturista
                                            : null);

                                    $permisosCount = $usuario->permissions->count();
                                @endphp

                                <tr>
                                    <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ($capturistas->currentPage() - 1) * $capturistas->perPage() + $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $perfil->nombre_completo ?? $usuario->name }}
                                                </div>
                                                @if (!$perfil)
                                                    <div class="text-xs text-red-500">Perfil incompleto</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        @switch($rol)
                                            @case('coordinador')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                                    </svg>
                                                    Coordinador
                                                </span>
                                            @break

                                            @case('capturista')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                                    </svg>
                                                    Capturista
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($rol) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center">
                                            <!-- MOSTRAR CONTADOR DE PERMISOS -->
                                            <span
                                                class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $permisosCount > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                {{ $permisosCount }} permiso(s)
                                            </span>
                                        </div>
                                    </td>
                                    @can('permisos.create')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <x-secondary-button :href="route('coordinador.permisos.create', [
                                                    'usuario' => $usuario->id,
                                                ])" wire:navigate>
                                                    Gestionar
                                                </x-secondary-button>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No se encontraron Usuarios
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
        @if (session('message'))
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
                                {{ session('message') }}
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
