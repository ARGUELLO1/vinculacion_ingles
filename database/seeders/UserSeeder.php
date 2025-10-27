<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear usuario administrador
        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('@dmin123'),
            'email_verified_at' => now(), // Opcional pero recomendado
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Crear registro en administradores
        DB::table('administradores')->insert([
            'user_id' => $userId,
            'nombre' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Asignar rol (asumiendo que usas spatie/laravel-permission)
        DB::table('model_has_roles')->insert([
            'role_id' => 1, // ID del rol administrador
            'model_type' => 'App\Models\User',
            'model_id' => $userId,
        ]);
    }
}
