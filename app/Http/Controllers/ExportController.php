<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AsistenciaExport; // 1. Importa el Export
use App\Models\Nivel; // 2. Importa el modelo Nivel (Grupo)
use Maatwebsite\Excel\Facades\Excel; // 3. Importa la fachada de Excel
use PDF;
use App\Models\Nota;

class ExportController extends Controller
{
    /**
     * Maneja la descarga del reporte de asistencias.
     */
    public function exportarAsistencias(Request $request, Nivel $grupo, $parcial)
    {
        // Validamos que el parcial sea 1, 2 o 3
        if (!in_array($parcial, [1, 2, 3])) {
            abort(404, 'Parcial no válido.');
        }

        // Seguridad: Verificamos que el profesor autenticado sea el dueño de este grupo
        if ($grupo->profesor_id !== auth()->user()->profesor->id_profesor) {
            abort(403, 'Acción no autorizada.');
        }

        // Nombre del archivo
        $nombreArchivo = "Asistencia_Parcial_{$parcial}_Grupo_{$grupo->nombre_grupo}.xlsx";

        // 4. Llama al Export y genera la descarga
        return Excel::download(
            new AsistenciaExport($grupo, $parcial), // Pasa el grupo y parcial
            $nombreArchivo
        );
    }
    public function exportarReportePDF(Request $request, Nivel $grupo, $parcial = null)
    {
        set_time_limit(120); // Prevención extra, por si el proceso tarda más

        // 1. Determinar el parcial a reportar
        $parcialNumero = (int)$parcial;
        $campoParcial = null;

        if ($parcialNumero === 0) { // Si no se pasó un parcial por URL (viene del botón sin parcial)
            if ($grupo->parcial_1 === '1') {
                $parcialNumero = 1;
                $campoParcial = 'nota_parcial_1';
            } elseif ($grupo->parcial_2 === '1') {
                $parcialNumero = 2;
                $campoParcial = 'nota_parcial_2';
            } elseif ($grupo->parcial_3 === '1') {
                $parcialNumero = 3;
                $campoParcial = 'nota_parcial_3';
            } else {
                return redirect()->back()->with('error', 'No hay un parcial activo para generar el reporte.');
            }
        } else {
            $campoParcial = 'nota_parcial_' . $parcialNumero;
        }

        // 2. Seguridad: Verificar que el profesor es dueño del grupo
        $profesor = $grupo->profesor;
        if ($profesor->id_profesor !== auth()->user()->profesor->id_profesor) {
            abort(403, 'Acción no autorizada.');
        }

        // 3. Obtener Alumnos y Calcular Estadísticas
        $alumnos = $grupo->alumnos;
        $totalAlumnos = $alumnos->count();
        $totalReprobados = 0;

        if ($totalAlumnos > 0) {
            $totalReprobados = Nota::where('nivel_id', $grupo->id_nivel)
                ->where($campoParcial, '>', 0)
                ->where($campoParcial, '<', 70)
                ->count();
        }

        $totalAprobados = $totalAlumnos - $totalReprobados;
        $porcentajeReprobados = ($totalAlumnos > 0) ? ($totalReprobados / $totalAlumnos) * 100 : 0;
        $porcentajeAprobados = 100 - $porcentajeReprobados;

        // 4. Configurar la gráfica (QuickChart.io)
        $chartConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Aprobados (' . $totalAprobados . ')', 'Reprobados (' . $totalReprobados . ')'],
                'datasets' => [[
                    'data' => [$totalAprobados, $totalReprobados],
                    'backgroundColor' => ['#4CAF50', '#F44336'],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ]],
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Distribución de Alumnos (Total: ' . $totalAlumnos . ')',
                    'font' => ['size' => 16]
                ],
                'plugins' => [
                    'datalabels' => [
                        'color' => 'white',
                        'font' => ['weight' => 'bold', 'size' => 14],
                        'formatter' => "function(value) { 
                        if (value === 0) return '0%'; 
                        return Math.round((value / " . ($totalAlumnos > 0 ? $totalAlumnos : 1) . ") * 100) + '%'; 
                    }",
                    ],
                ],
            ],
        ];

        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig)) .
            '&width=500&height=300&backgroundColor=white&version=2.9.4';

        // 5. Descargar la imagen de la gráfica con timeout (cURL)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $chartUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // máximo 5 segundos
        $chartImage = curl_exec($ch);
        curl_close($ch);

        // Si hubo error o no se generó imagen, lo manejamos
        if (!$chartImage) {
            $chartBase64 = null;
        } else {
            $chartBase64 = base64_encode($chartImage);
        }

        // 6. Preparar los datos para la vista PDF
        $data = [
            'grupo' => $grupo,
            'profesor' => $profesor,
            'parcialNumero' => $parcialNumero,
            'totalAlumnos' => $totalAlumnos,
            'totalReprobados' => $totalReprobados,
            'porcentajeAprobados' => $porcentajeAprobados,
            'porcentajeReprobados' => $porcentajeReprobados,
            'chartBase64' => $chartBase64,
        ];

        // 7. Generar PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.reporte-grupo', $data);

        $nombreArchivo = "Reporte_Grupo_{$grupo->nombre_grupo}_Parcial_{$parcialNumero}.pdf";

        return $pdf->download($nombreArchivo);
    }
}
