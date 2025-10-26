<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusAlumnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estatus = [
            ['tipo_estatus_alumno' => 'Activo'],
            ['tipo_estatus_alumno' => 'Inactivo'],
            ['tipo_estatus_alumno' => 'Egresado'],
            ['tipo_estatus_alumno' => 'Baja Temporal'],
            ['tipo_estatus_alumno' => 'Baja Definitiva'],
        ];

        DB::table('estatus_alumnos')->insert($estatus);
    }
}