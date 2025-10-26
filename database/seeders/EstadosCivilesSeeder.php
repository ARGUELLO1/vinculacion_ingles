<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosCivilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['tipo_estado_civil' => 'Soltero/a'],
            ['tipo_estado_civil' => 'Casado/a'],
            ['tipo_estado_civil' => 'Divorciado/a'],
            ['tipo_estado_civil' => 'Viudo/a'],
            ['tipo_estado_civil' => 'Unión Libre'],
        ];

        DB::table('estados_civiles')->insert($estados);
    }
}