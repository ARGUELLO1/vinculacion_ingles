<?php

namespace App\Livewire\Forms\Admin\Permisos;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Permission;

class PermisosForm extends Form
{
    public $user;
    public $usuarioName;
    public $selectedPermissions = [];
    public $permissionGroups = [];

    public $groupNames = [
        'capturistas' => 'Permisos de Capturistas',
        'profesores' => 'Permisos de Profesores',
        'niveles' => 'Permisos de Niveles',
        'permisos' => 'Permisos del Sistema'
    ];

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->usuarioName = $user->name;
        $this->selectedPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        $this->loadPermissionGroups();

        // Aplicar lógica inicial de .options
        $this->applyOptionsLogic();
    }

    public function loadPermissionGroups()
    {
        $allPermissions = Permission::all();
        $userRole = $this->user->roles->first()->name ?? null;

        foreach ($this->groupNames as $groupKey => $groupName) {
            if ($this->shouldShowGroup($groupKey, $userRole)) {
                $this->permissionGroups[$groupKey] = [
                    'name' => $groupName,
                    'permissions' => $allPermissions->filter(function ($permission) use ($groupKey) {
                        // FILTRAR: Excluir permisos que contengan .options
                        return str_contains($permission->name, $groupKey) &&
                            !str_contains($permission->name, '.options');
                    })->values()
                ];
            }
        }
    }

    public function shouldShowGroup($groupKey, $userRole)
    {
        switch ($userRole) {
            case 'coordinador':
                return in_array($groupKey, ['capturistas', 'profesores', 'alumnos', 'niveles', 'permisos']);
            case 'capturista':
                return $groupKey === 'niveles';
            case 'profesor':
                return $groupKey === 'alumnos';
            case 'alumno':
                return false;
            default:
                return false;
        }
    }

    public function save()
    {
        $this->validate([
            'selectedPermissions' => 'array'
        ]);

        // Aplicar lógica de .options antes de guardar
        $this->applyOptionsLogic();

        $this->user->syncPermissions($this->selectedPermissions);
    }

    /**
     * Lógica para manejar automáticamente los permisos .options
     * Se asigna .options si tiene update o delete
     * Se remueve .options si no tiene ni update ni delete
     */
    public function applyOptionsLogic()
    {
        $groupsToProcess = ['capturistas', 'profesores', 'niveles', 'permisos'];

        foreach ($groupsToProcess as $group) {
            $this->processGroupOptions($group);
        }
    }

    /**
     * Procesa la lógica de .options para un grupo específico
     */
    private function processGroupOptions($group)
    {
        $hasUpdate = in_array("{$group}.update", $this->selectedPermissions);
        $hasDelete = in_array("{$group}.delete", $this->selectedPermissions);
        $hasOptions = in_array("{$group}.options", $this->selectedPermissions);

        $optionsPermission = "{$group}.options";

        if (($hasUpdate || $hasDelete) && !$hasOptions) {
            // Si tiene update o delete pero no tiene options, agregar options
            if (!in_array($optionsPermission, $this->selectedPermissions)) {
                $this->selectedPermissions[] = $optionsPermission;
            }
        } elseif (!$hasUpdate && !$hasDelete && $hasOptions) {
            // Si no tiene ni update ni delete pero tiene options, remover options
            $this->selectedPermissions = array_filter(
                $this->selectedPermissions,
                fn($perm) => $perm !== $optionsPermission
            );
        }
    }

    /**
     * Método para manejar cambios en tiempo real
     */
    public function updated($property, $value)
    {
        // Si cambian los selectedPermissions, aplicar lógica de options
        if ($property === 'selectedPermissions') {
            $this->applyOptionsLogic();
        }
    }

    public function selectAllInGroup($groupKey)
    {
        $groupPermissions = $this->permissionGroups[$groupKey]['permissions']
            ->pluck('name')
            ->toArray();

        foreach ($groupPermissions as $permission) {
            if (!in_array($permission, $this->selectedPermissions)) {
                $this->selectedPermissions[] = $permission;
            }
        }

        // Aplicar lógica de options después de seleccionar todos
        $this->processGroupOptions($groupKey);
    }

    public function clearAllInGroup($groupKey)
    {
        $groupPermissions = $this->permissionGroups[$groupKey]['permissions']
            ->pluck('name')
            ->toArray();

        $this->selectedPermissions = array_filter(
            $this->selectedPermissions,
            fn($perm) => !in_array($perm, $groupPermissions)
        );

        // También remover .options si existe
        $optionsPermission = "{$groupKey}.options";
        $this->selectedPermissions = array_filter(
            $this->selectedPermissions,
            fn($perm) => $perm !== $optionsPermission
        );
    }

    public function getPermissionDisplayName($permissionName)
    {
        $names = [
            'index' => 'Visualizar',
            'create' => 'Crear',
            'update' => 'Editar',
            'delete' => 'Eliminar',
            'show' => 'Ver detalle'
        ];

        foreach ($names as $key => $display) {
            if (str_contains($permissionName, $key)) {
                return $display;
            }
        }

        return str_replace(['.', '_'], ' ', $permissionName);
    }

    public function getTotalPermissionsProperty()
    {
        return collect($this->permissionGroups)->sum(function ($group) {
            return $group['permissions']->count();
        });
    }

    /**
     * Método para obtener el total REAL de permisos (incluyendo .options automáticos)
     */
    public function getTotalRealPermissionsProperty()
    {
        $total = 0;
        foreach ($this->permissionGroups as $group) {
            $total += $group['permissions']->count();
        }
        return $total;
    }

    /**
     * Método para obtener permisos automáticos actuales
     */
    public function getAutoPermissionsProperty()
    {
        return array_filter($this->selectedPermissions, function ($perm) {
            return str_contains($perm, '.options');
        });
    }
}