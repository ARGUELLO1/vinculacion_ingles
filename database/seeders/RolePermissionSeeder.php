<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
    }
}