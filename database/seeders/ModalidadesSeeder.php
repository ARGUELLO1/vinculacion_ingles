<?php

namespace Database\Seeders;

use App\Models\Modalidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modalidades = [
            'Semestral/Presencial',
            'Semestral/En linea',
            'Semestral/Mixto',
            'Intersemestral/Presencial',
            'Intersemestral/En linea',
            'Intersemestral/Mixto',
            'Sabatino/Presencial',
            'Sabatino/En linea',
            'Sabatino/Mixto',
            'Curso Intensivo/Presencial',
            'Curso Intensivo/En linea',
            'Curso Intensivo/Mixto',
        ];

        foreach ($modalidades as $modalidad) {
            Modalidad::create([
                'tipo_modalidad' => $modalidad
            ]);
        }
    }
}