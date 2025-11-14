<?php

namespace App\Livewire\Admin\Documentos;

use App\Exports\AlumnosNotasExport;
use App\Models\Nivel;
use App\Models\Nota;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Alumnos extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedNivel = null;
    public $showActionsModal = false;
    public $showDownloadModal = false;

    // Listeners para comunicación con modales
    protected $listeners = [
        'closeActionsModal' => 'closeActionsModal',
        'closeDownloadModal' => 'closeDownloadModal',
        'generarActa' => 'generarActa',
        'generarConstancia' => 'generarConstancia',
        'generarPDF' => 'generarPDF',
        'generarExcel' => 'generarExcel'
    ];

    public function render()
    {
        $niveles = Nivel::withCount(['alumnos'])
            ->with(['periodo', 'profesor'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nivel', 'like', '%' . $this->search . '%')
                        ->orWhere('nombre_grupo', 'like', '%' . $this->search . '%')
                        ->orWhere('aula', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('nivel')
            ->orderBy('nombre_grupo')
            ->paginate($this->perPage);

        // Obtener estadísticas de notas para todos los niveles
        $nivelIds = $niveles->pluck('id_nivel')->toArray();

        $estadisticas = Nota::whereIn('nivel_id', $nivelIds)
            ->selectRaw('
                nivel_id,
                COUNT(*) as total_alumnos,
                SUM(CASE WHEN (nota_parcial_1 + nota_parcial_2 + nota_parcial_3) / 3 >= 70 THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN (nota_parcial_1 + nota_parcial_2 + nota_parcial_3) / 3 < 70 THEN 1 ELSE 0 END) as reprobados
            ')
            ->groupBy('nivel_id')
            ->get()
            ->keyBy('nivel_id');

        // Asignar estadísticas a cada nivel
        $niveles->getCollection()->transform(function ($nivel) use ($estadisticas) {
            $stats = $estadisticas->get($nivel->id_nivel);

            $nivel->aprobados_count = $stats ? $stats->aprobados : 0;
            $nivel->reprobados_count = $stats ? $stats->reprobados : 0;
            $nivel->total_con_calificaciones = $stats ? $stats->total_alumnos : 0;

            return $nivel;
        });

        return view('livewire.admin.documentos.alumnos', [
            'niveles' => $niveles
        ]);
    }

    public function toggleActions($nivelId)
    {
        $this->selectedNivel = $nivelId;
        $this->showActionsModal = true;

        // Emitir evento al modal global
        $this->dispatch('openActionsModal', nivelId: $nivelId);
    }

    public function closeActionsModal()
    {
        $this->showActionsModal = false;
        $this->selectedNivel = null;
    }

    public function toggleDownload($nivelId)
    {
        $this->selectedNivel = $nivelId;
        $this->showDownloadModal = true;

        // Emitir evento al modal global
        $this->dispatch('openDownloadModal', nivelId: $nivelId);
    }

    public function closeDownloadModal()
    {
        $this->showDownloadModal = false;
        $this->selectedNivel = null;
    }

    // Métodos que serán llamados desde los modales globales
    public function generarActa($nivelId)
    {
        $this->redirectRoute('admin.documentos.actas', $nivelId, navigate: true);
    }

    public function generarConstancia($nivelId)
    {
        $this->redirectRoute('admin.documentos.constancias', $nivelId, navigate: true);
    }

    public function generarPDF($nivelId)
    {
        try {
            $nivel = Nivel::with([
                'alumnos.nota' => function ($query) use ($nivelId) {
                    $query->where('nivel_id', $nivelId);
                },
                'alumnos.asistencia' => function ($query) use ($nivelId) {
                    $query->where('nivel_id', $nivelId);
                },
                'profesor'
            ])->find($nivelId);

            if (!$nivel) {
                session()->flash('error', 'Nivel no encontrado');
                return;
            }

            $alumnos = $nivel->alumnos;

            // Calcular total de asistencias por alumno (contando 'A' como asistencia)
            foreach ($alumnos as $alumno) {
                $asistenciasAlumno = $alumno->asistencia->where('nivel_id', $nivelId);
                $alumno->total_asistencias = $asistenciasAlumno->where('asistencia', 'A')->count();
                $alumno->total_faltas = $asistenciasAlumno->where('asistencia', 'F')->count();
                $totalRegistros = $asistenciasAlumno->count();
                $alumno->porcentaje_asistencia = $totalRegistros > 0 ? ($alumno->total_asistencias / $totalRegistros) * 100 : 0;
            }

            $pdf = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'isPhpEnabled' => true
            ])->loadView('pdf.alumnos-notas', [
                'nivel' => $nivel,
                'alumnos' => $alumnos
            ]);

            $this->closeDownloadModal();

            return response()->streamDownload(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                "alumnos-nivel-{$nivel->nivel}-{$nivel->nombre_grupo}.pdf",
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="alumnos.pdf"'
                ]
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar PDF: ' . $e->getMessage());
            $this->closeDownloadModal();
        }
    }

    public function generarExcel($nivelId)
    {
        try {
            $nivel = Nivel::find($nivelId);
            $this->closeDownloadModal();
            return Excel::download(new AlumnosNotasExport($nivelId), "alumnos-nivel-{$nivel->nivel}-{$nivel->nombre_grupo}.xlsx");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar Excel: ' . $e->getMessage());
            $this->closeDownloadModal();
        }
    }
}
