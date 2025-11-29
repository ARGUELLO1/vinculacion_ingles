<?php

namespace App\Livewire\Profesor;

use App\Models\Nivel;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Alumno;
use App\Models\DocumentoProfesor;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\WithPagination;

class GrupoVista extends Component
{
    use WithPagination;

    public Nivel $grupo;
    public string $search = '';
    public array $calificaciones = [];
    public ?string $parcialActivo = null;
    public bool $filtroReprobados = false;

    public string $modo = 'calificaciones';
    public string $fechaAsistencia;
    public array $asistenciasHoy = [];
    public int $parcialActivoNumero = 0;

    public bool $isConcluido = false;

    protected array $rules = [
        'calificaciones.*.nota_parcial_1' => 'nullable|numeric|min:0|max:100.0',
        'calificaciones.*.nota_parcial_2' => 'nullable|numeric|min:0|max:100.0',
        'calificaciones.*.nota_parcial_3' => 'nullable|numeric|min:0|max:100.0',
    ];


    public function mount(Nivel $grupo)
    {
        $this->grupo = $grupo;
        $this->isConcluido = $this->grupo->nivel_concluido ?? false;

        if ($this->grupo->parcial_1 === '1') {
            $this->parcialActivo = 'nota_parcial_1';
            $this->parcialActivoNumero = 1;
        } elseif ($this->grupo->parcial_2 === '1') {
            $this->parcialActivo = 'nota_parcial_2';
            $this->parcialActivoNumero = 2;
        } elseif ($this->grupo->parcial_3 === '1') {
            $this->parcialActivo = 'nota_parcial_3';
            $this->parcialActivoNumero = 3;
        }

        $notasDelGrupo = Nota::where('nivel_id', $this->grupo->id_nivel)->get();
        foreach ($notasDelGrupo as $nota) {
            $this->calificaciones[$nota->alumno_id] = [
                'nota_parcial_1' => $nota->nota_parcial_1 ?? null,
                'nota_parcial_2' => $nota->nota_parcial_2 ?? null,
                'nota_parcial_3' => $nota->nota_parcial_3 ?? null,
            ];
        }
        // Obtener todos los alumnos del grupo
        $alumnos = $this->grupo->alumnos()->get();

        foreach ($alumnos as $alumno) {

            // Si el alumno no tiene nota, la creamos
            $nota = Nota::firstOrCreate(
                [
                    'alumno_id' => $alumno->id_alumno,
                    'nivel_id'  => $this->grupo->id_nivel,
                ],
                [
                    'nota_parcial_1' => 0,
                    'nota_parcial_2' => 0,
                    'nota_parcial_3' => 0,
                ]
            );

            // Cargar la nota en el arreglo local
            $this->calificaciones[$alumno->id_alumno] = [
                'nota_parcial_1' => $nota->nota_parcial_1,
                'nota_parcial_2' => $nota->nota_parcial_2,
                'nota_parcial_3' => $nota->nota_parcial_3,
            ];
        }

        $this->fechaAsistencia = now()->format('Y-m-d');
        $this->cargarAsistencias();
    }

    public function setModo(string $modo)
    {
        $this->modo = $modo;
        $this->resetPage();
    }

    public function cargarAsistencias()
    {
        $this->asistenciasHoy = Asistencia::where('nivel_id', $this->grupo->id_nivel)
            ->where('fecha', $this->fechaAsistencia)
            ->pluck('asistencia', 'alumno_id')
            ->toArray();
    }

    public function updatedFechaAsistencia()
    {
        $this->cargarAsistencias();
    }

    public function guardarAsistencia($alumnoId, $estatus)
    {
        if ($this->isConcluido) {
            $this->dispatch('alerta-error', ['mensaje' => 'Este grupo está concluido y no se puede modificar.']);
            return;
        }

        if ($this->parcialActivoNumero === 0) {
            $this->dispatch('alerta-error', ['mensaje' => 'No hay un parcial activo para registrar asistencia.']);
            return;
        }

        Asistencia::updateOrCreate(
            [
                'alumno_id' => $alumnoId,
                'nivel_id'  => $this->grupo->id_nivel,
                'fecha'     => $this->fechaAsistencia,
            ],
            [
                'parcial'    => $this->parcialActivoNumero,
                'asistencia' => $estatus,
            ]
        );

        $this->asistenciasHoy[$alumnoId] = $estatus;
        $this->dispatch('alerta-exito', ['mensaje' => 'Asistencia guardada correctamente.']);
    }

    public function updatedCalificaciones($value, $key)
    {
        if ($this->isConcluido) {
            $this->dispatch('alerta-error', ['mensaje' => 'Este grupo está concluido y no se puede modificar.']);
            return;
        }

        $this->validateOnly('calificaciones.' . $key);

        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        [$alumnoId, $campo] = $parts;

        if (!isset($this->calificaciones[$alumnoId])) {
            $this->calificaciones[$alumnoId] = [
                'nota_parcial_1' => null,
                'nota_parcial_2' => null,
                'nota_parcial_3' => null,
            ];
        }

        $this->calificaciones[$alumnoId][$campo] = $value === '' ? null : $value;

        $notasDelAlumno = $this->calificaciones[$alumnoId];

        Nota::updateOrCreate(
            [
                'alumno_id' => $alumnoId,
                'nivel_id'  => $this->grupo->id_nivel,
            ],
            [
                'nota_parcial_1' => $notasDelAlumno['nota_parcial_1'] ?? 0,
                'nota_parcial_2' => $notasDelAlumno['nota_parcial_2'] ?? 0,
                'nota_parcial_3' => $notasDelAlumno['nota_parcial_3'] ?? 0,
            ]
        );

        $this->dispatch('alerta-exito', ['mensaje' => '¡Calificación actualizada con éxito!']);
    }

    public function toggleFiltroReprobados()
    {
        $this->filtroReprobados = !$this->filtroReprobados;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function descargarPlaneacion()
    {

        $documento = DocumentoProfesor::where('nivel_id', $this->grupo->id_nivel)
            ->where('tipo_doc', 'planeacion')
            ->first();

        if (!$documento) {
            $this->dispatch('alerta-error', ['mensaje' => 'No se encontró el documento de planeación.']);
            return;
        }


        $rutaRelativa = 'expedientesProfesores/' . $documento->ruta_doc;

        $rutaNormalizada = str_replace('/', DIRECTORY_SEPARATOR, $rutaRelativa);


        $rutaAbsolutaManual = storage_path('app') . DIRECTORY_SEPARATOR . $rutaNormalizada;


        if (!file_exists($rutaAbsolutaManual)) {

            $this->dispatch('alerta-error', ['mensaje' => 'Archivo no encontrado (verificación manual).']);
            return;
        }


        return response()->download($rutaAbsolutaManual);
    }
    public function render()
    {


        if ($this->isConcluido) {
            $alumnoIds = Nota::where('nivel_id', $this->grupo->id_nivel)
                ->pluck('alumno_id')
                ->unique();

            $alumnosQuery = Alumno::whereIn('id_alumno', $alumnoIds)
                ->with('carrera');
        } else {
            $alumnosQuery = $this->grupo->alumnos()
                ->with('carrera');
        }

        $alumnosQuery->when($this->search, function ($query) {
            $query->where(function ($sub) {
                $sub->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('ap_paterno', 'like', "%{$this->search}%")
                    ->orWhere('ap_materno', 'like', "%{$this->search}%");
            });
        });

        if ($this->filtroReprobados && $this->modo === 'calificaciones' && $this->parcialActivo) {
            $alumnosQuery->whereHas('notas', function ($q) {
                $q->where('nivel_id', $this->grupo->id_nivel)
                    ->where($this->parcialActivo, '>', 0)
                    ->where($this->parcialActivo, '<', 70);
            });
        }

        $alumnosQuery->orderBy('ap_paterno')->orderBy('ap_materno')->orderBy('nombre');
        return view('livewire.profesor.grupo-vista', [
            'alumnos' => $alumnosQuery->paginate(15),
            'parcialActivo' => $this->parcialActivo,
        ]);
    }
}
