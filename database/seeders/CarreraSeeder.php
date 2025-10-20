<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            [
                'nombre_carrera' => 'Ingeniería en Sistemas Computacionales'
            ],
            [
                'nombre_carrera' => 'Ingenieria Ambiental',
            ],
            [
                'nombre_carrera' => 'Ingeniería Electrónica',
            ],
            [
                'nombre_carrera' => 'Ingeniería Biomédica',
            ],
            [
                'nombre_carrera' => 'Ingeniería Informática',
            ],
            [
                'nombre_carrera' => 'Licenciatura en Administración',
            ],
            [
                'nombre_carrera' => 'Arquitectura',
            ],
        ];

        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }
    }
}
