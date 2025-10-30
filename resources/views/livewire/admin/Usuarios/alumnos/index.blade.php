<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Gestión de Alumnos
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
                            <x-text-input wire:model.live="search" placeholder="Buscar alumnos..."
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
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell lg:table-cell">
                                    Matricula
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell lg:table-cell">
                                    Estatus
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell lg:table-cell">
                                    Sexo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nivel
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($alumnos as $user)
                                <tr>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        {{ $user->alumno?->id_alumno ?? 'N/A' }}
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        {{ $user->alumno?->nombre_completo ?? $user->name }}
                                    </td>
                                    <td
                                        class="text-center px-6 py-4 whitespace-nowrap hidden md:table-cell lg:table-cell">
                                        {{ $user->alumno?->matricula ?? '' }}
                                    </td>
                                    <td
                                        class="text-center px-6 py-4 whitespace-nowrap hidden md:table-cell lg:table-cell">
                                        {{ $user->alumno?->estatus->tipo_estatus_alumno ?? '' }}
                                    </td>
                                    <td
                                        class="text-center px-6 py-4 whitespace-nowrap hidden md:table-cell lg:table-cell">
                                        {{ $user->alumno?->sexo ?? '' }}
                                    </td>
                                    <td class="text-center px-6 py-4 whitespace-nowrap">
                                        {{ $user->alumno?->nivel->nivel ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron Alumnos
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $alumnos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
