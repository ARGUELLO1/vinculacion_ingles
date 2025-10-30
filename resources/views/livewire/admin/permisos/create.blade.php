<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-7xl">

                <!-- Header -->
                <header class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                Gestión de Permisos
                            </h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Usuario: <span class="font-semibold">{{ $user->name }}</span> |
                                Email: <span class="font-semibold">{{ $user->email }}</span> |
                                Rol: <span class="font-semibold">{{ $user->roles->first()->name ?? 'Sin rol' }}</span>
                            </p>
                            <!-- Mensaje informativo sobre permisos disponibles -->
                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-sm text-yellow-800">
                                    <strong>Nota:</strong> Se muestran solo los permisos disponibles para el rol de
                                    <span class="font-semibold">{{ $user->roles->first()->name ?? 'Sin rol' }}</span>
                                </p>
                            </div>
                        </div>
                        <x-danger-button :href="route('admin.permisos.index')" wire:navigate>
                            ← Volver a la lista
                        </x-danger-button>
                    </div>
                </header>

                <!-- Mensajes de éxito -->
                @if (session()->has('message'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Resumen de permisos seleccionados -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">Resumen de Permisos</h3>
                            <p class="text-blue-700">
                                <span class="font-bold">{{ count($form->selectedPermissions) }}</span> permisos
                                seleccionados
                                de
                                <span class="font-bold">{{ $form->getTotalRealPermissionsProperty() }}</span>
                                disponibles para este rol
                            </p>
                            <!-- Mostrar permisos automáticos si existen -->
                            @php
                                $autoPermissions = array_filter($form->selectedPermissions, function ($perm) {
                                    return str_contains($perm, '.options');
                                });
                            @endphp
                            @if (count($autoPermissions) > 0)
                                <p class="text-sm text-blue-600 mt-1">
                                    + <span class="font-bold">{{ count($autoPermissions) }}</span> permiso(s)
                                    automático(s)
                                </p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" wire:click="$set('form.selectedPermissions', [])"
                                class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                                Limpiar Todos
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formulario de permisos -->
                <section>
                    <form wire:submit="save" class="space-y-8">

                        @forelse ($form->permissionGroups as $groupKey => $group)
                            @if ($group['permissions']->count() > 0)
                                <div class="border border-gray-200 rounded-lg p-6">

                                    <!-- Header del grupo -->
                                    <div class="flex justify-between items-center mb-4 pb-3 border-b">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-800">
                                                {{ $group['name'] }}
                                            </h3>
                                            @php
                                                $groupPermissionNames = $group['permissions']->pluck('name')->toArray();
                                                $selectedInGroup = count(
                                                    array_intersect($form->selectedPermissions, $groupPermissionNames),
                                                );
                                                // Contar también el options automático si existe
                                                $hasAutoOptions = in_array(
                                                    "{$groupKey}.options",
                                                    $form->selectedPermissions,
                                                );
                                            @endphp
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $selectedInGroup }} de {{ count($groupPermissionNames) }} permisos
                                                seleccionados
                                                @if ($hasAutoOptions)
                                                    <span class="text-blue-600">+ 1 automático</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" wire:click="selectAllInGroup('{{ $groupKey }}')"
                                                class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200">
                                                Todos
                                            </button>
                                            <button type="button" wire:click="clearAllInGroup('{{ $groupKey }}')"
                                                class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                                                Ninguno
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Grid de permisos -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach ($group['permissions'] as $permission)
                                            <label
                                                class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg border border-gray-100 cursor-pointer transition-colors">
                                                <input type="checkbox" value="{{ $permission->name }}"
                                                    wire:model="form.selectedPermissions"
                                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <div class="flex-1">
                                                    <span class="text-sm font-medium text-gray-900 block">
                                                        {{ $form->getPermissionDisplayName($permission->name) }}
                                                    </span>
                                                    @if ($permission->description)
                                                        <span class="text-xs text-gray-500 block mt-1">
                                                            {{ $permission->description }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">Sin permisos disponibles</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No hay permisos disponibles para asignar al rol de
                                    <span class="font-semibold">{{ $user->roles->first()->name ?? 'Sin rol' }}</span>.
                                </p>
                            </div>
                        @endforelse

                        <!-- Botones de acción -->
                        @if (count($form->permissionGroups) > 0)
                            <div class="flex items-center justify-end gap-4 pt-6 border-t">
                                <x-danger-button :href="route('admin.permisos.index')" wire:navigate>
                                    {{ __('Cancelar') }}
                                </x-danger-button>
                                <x-primary-button>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Guardar Permisos') }}
                                </x-primary-button>
                            </div>
                        @endif
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
