<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CarrerasSeeder::class,
            EstadosCivilesSeeder::class,
            EstatusAlumnosSeeder::class,
            // Agrega aquí otros seeders que necesites
        ]);
    }
}