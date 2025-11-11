<?php

namespace App\Exports;

use App\Models\Nivel;

use App\Models\Asistencia;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class AsistenciaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Nivel $grupo;
    protected int $parcial;
    protected Collection $fechas; // Almacenará las fechas (como strings YYYY-MM-DD)
    protected Collection $alumnos;
    protected Collection $asistenciasMap;

    public function __construct(Nivel $grupo, int $parcial)
    {
        $this->grupo = $grupo;
        $this->parcial = $parcial;


        $alumnosActuales = $this->grupo->alumnos()
            ->pluck('id_alumno');

        $alumnosConAsistencia = Asistencia::where('nivel_id', $this->grupo->id_nivel)
            ->where('parcial', $this->parcial)
            ->pluck('alumno_id');

        $idsUnicos = $alumnosActuales->merge($alumnosConAsistencia)->unique();

        $this->alumnos = \App\Models\Alumno::whereIn('id_alumno', $idsUnicos)
            ->orderBy('ap_paterno')
            ->orderBy('ap_materno')
            ->orderBy('nombre')
            ->get();

        // 2. Obtenemos TODAS las asistencias para este grupo y parcial
        $todasLasAsistencias = Asistencia::where('nivel_id', $this->grupo->id_nivel)
            ->where('parcial', $this->parcial)
            ->get();

        // 3. Obtenemos las fechas únicas (como strings YYYY-MM-DD)
        // Usamos ->format('Y-m-d') para asegurarnos de que sean strings
        // Si 'fecha' no es un objeto Carbon, lo parseamos primero
        $this->fechas = $todasLasAsistencias
            ->pluck('fecha')
            ->map(fn($fecha) => ($fecha instanceof Carbon ? $fecha : Carbon::parse($fecha))->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values();

        // 4. Creamos un mapa [id_alumno => [fecha_Y-m-d => 'A', fecha_Y-m-d => 'F']]
        $this->asistenciasMap = $todasLasAsistencias
            ->groupBy('alumno_id') // Agrupa por alumno
            ->map(function ($asistenciasDelAlumno) {
                // 'fecha' es un string 'YYYY-MM-DD' en la BD (o lo forzamos a serlo)
                return $asistenciasDelAlumno
                    ->mapWithKeys(function ($asistencia) {
                        $fechaKey = Carbon::parse($asistencia->fecha)->format('Y-m-d');
                        return [$fechaKey => $asistencia->asistencia];
                    });
            });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->alumnos;
    }

    /**
     * Mapea los datos de cada alumno a una fila del Excel
     * @var \App\Models\Alumno $alumno
     */
    public function map($alumno): array
    {
        // 1. Datos básicos
        $fila = [
            $alumno->ap_paterno,
            $alumno->ap_materno,
            $alumno->nombre,
            $this->grupo->nombre_grupo,
        ];

        // Si no hay fechas, añadimos nota y terminamos
        if ($this->fechas->isEmpty()) {
            $fila[] = 'No hay asistencias registradas para este parcial.';
            return $fila;
        }

        // 2. Obtenemos el mapa de asistencias solo para este alumno
        $asistenciasDelAlumno = $this->asistenciasMap->get($alumno->id_alumno, collect());

        // 3. Iteramos sobre las fechas (que AHORA son strings 'YYYY-MM-DD')
        foreach ($this->fechas as $fechaString) {

            // 4. Buscamos usando el string 'YYYY-MM-DD' como clave
            $fila[] = $asistenciasDelAlumno->get($fechaString, 'N/R');
        }

        return $fila;
    }

    /**
     * Define los encabezados de las columnas
     */
    public function headings(): array
    {
        // 1. Encabezados fijos
        $encabezados = [
            'Apellido Paterno',
            'Apellido Materno',
            'Nombre',
            'Grupo',
        ];

        // Si no hay fechas, añadimos nota y terminamos
        if ($this->fechas->isEmpty()) {
            $encabezados[] = 'Notas';
            return $encabezados;
        }

        // 2. Encabezados dinámicos (las fechas)
        // $fechaString es 'YYYY-MM-DD', lo parseamos para darle formato
        foreach ($this->fechas as $fechaString) {
            $encabezados[] = Carbon::parse($fechaString)->format('d-m-Y');
        }

        return $encabezados;
    }
}
