<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            '2025-2026/1',
            '2025-2026/2',
            '2026-2027/1',
            '2026-2027/2',
            '2027-2028/1',
            '2027-2028/2',
            '2028-2029/1',
            '2028-2029/2',
            '2029-2030/1',
            '2029-2030/2',
            '2030-2031/1',
            '2030-2031/2',
            '2031-2032/1',
            '2031-2032/2',
            '2032-2033/1',
            '2032-2033/2',
            '2033-2034/1',
            '2033-2034/2',
            '2034-2035/1',
            '2034-2035/2',
        ];

        foreach ($periodos as $periodo) {
            Periodo::create([
                'periodo' => $periodo
            ]);
        }
    }
}