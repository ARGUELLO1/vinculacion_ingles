<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $coordinadorRole = Role::create(['name' => 'coordinador']);
        $capturistaRole = Role::create(['name' => 'capturista']);
        $profesorRole = Role::create(['name' => 'profesor']);
        $alumnoRole = Role::create(['name' => 'alumno']);

        //Permisos sobre coordinadores
        Permission::create(['name' => 'coordinadores.index', 'descripcion' => 'Poder visualizar Coordinadores'])->syncRoles($adminRole);
        Permission::create(['name' => 'coordinadores.create', 'descripcion' => 'Poder crear Coordinadores'])->syncRoles($adminRole);
        Permission::create(['name' => 'coordinadores.update', 'descripcion' => 'Poder editar Coordinadores'])->syncRoles($adminRole);
        Permission::create(['name' => 'coordinadores.delete', 'descripcion' => 'Poder eliminar Coordinadores'])->syncRoles($adminRole);

        //Permisos sobre capturistas
        Permission::create(['name' => 'capturistas.index', 'descripcion' => 'Poder visualizar Capturistas'])->syncRoles($adminRole, $coordinadorRole);

        //Coordinador solo con permisos concedidos por el administrador
        Permission::create(['name' => 'capturistas.create', 'descripcion' => 'Poder crear Capturistas'])->syncRoles($adminRole);
        Permission::create(['name' => 'capturistas.update', 'descripcion' => 'Poder editar Capturistas'])->syncRoles($adminRole);
        Permission::create(['name' => 'capturistas.delete', 'descripcion' => 'Poder eliminar Capturistas'])->syncRoles($adminRole);

        //Permisos sobre profesores
        Permission::create(['name' => 'profesores.index', 'descripcion' => 'Poder visualizar Profesores'])->syncRoles($adminRole, $coordinadorRole);
        Permission::create(['name' => 'profesores.create', 'descripcion' => 'Poder crear Profesores'])->syncRoles($adminRole);
        Permission::create(['name' => 'profesores.update', 'descripcion' => 'Poder editar Profesores'])->syncRoles($adminRole, $profesorRole);
        Permission::create(['name' => 'profesores.delete', 'descripcion' => 'Poder eliminar Profesores'])->syncRoles($adminRole);

        //Permisos sobre alumnos
        Permission::create(['name' => 'alumnos.index', 'descripcion' => 'Poder visualizar Alumnos'])->syncRoles($adminRole, $coordinadorRole);
        Permission::create(['name' => 'alumnos.update', 'descripcion' => 'Poder editar Alumnos'])->syncRoles($alumnoRole);

        //Permisos sobre niveles
        Permission::create(['name' => 'niveles.index', 'descripcion' => 'Poder visualizar Niveles'])->syncRoles($adminRole, $coordinadorRole, $capturistaRole);
        Permission::create(['name' => 'niveles.create', 'descripcion' => 'Poder crear Niveles'])->syncRoles($capturistaRole);

        //Solo con permisos concedidos por el administrador o el coordinador
        Permission::create(['name' => 'niveles.update', 'descripcion' => 'Poder editar Niveles']);

        //Permisos sobre permisos
        Permission::create(['name' => 'permisos.index', 'descripcion' => 'Poder visualizar Usuarios'])->syncRoles($adminRole, $coordinadorRole);

        //Coordinador solo con permisos concedidos por el administrador
        Permission::create(['name' => 'permisos.create', 'descripcion' => 'Poder conceder permisos'])->syncRoles($adminRole);
        Permission::create(['name' => 'permisos.update', 'descripcion' => 'Poder editar permisos'])->syncRoles($adminRole);
    }
}
