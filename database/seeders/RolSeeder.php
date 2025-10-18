<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            1 => [
                'tipo_rol' => 'administrador',
            ],
            2 => [
                'tipo_rol' => 'capturista',
            ],
            3 => [
                'tipo_rol' => 'profesor',
            ],
            4 => [
                'tipo_rol' => 'alumno',
            ],
        ];

        foreach ($roles as $id => $roleData) {
            Rol::updateOrCreate(
                ['id_rol' => $id],
                $roleData
            );

            $this->command->info("Rol procesado: {$roleData['tipo_rol']}");
        }

        $this->command->info('¡Seeder de roles completado exitosamente!');
        $this->command->info('Roles creados: ' . Rol::count());
    }
}