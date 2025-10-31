<?php

namespace App\Livewire\Profesor;

//Modelos sisi :v
use App\Models\Nivel;
use App\Models\Nota;
use App\Models\Asistencia; 
//componentes
use Livewire\Component;
use Livewire\WithPagination;

class GrupoVista extends Component
{
    use WithPagination; 

    // Propiedades existentes
    public Nivel $grupo;
    public string $search = '';
    public array $calificaciones = [];
    public ?string $parcialActivo = null;
    public bool $filtroReprobados = false;
    

    public string $modo = 'calificaciones'; // 'calificaciones' o 'asistencia'
    public string $fechaAsistencia; // Formato Y-m-d
    public array $asistenciasHoy = []; // Guarda [alumno_id => 'A'] o [alumno_id => 'F']
    public int $parcialActivoNumero = 0; // 1, 2, o 3

    protected array $rules = [
        'calificaciones.*.nota_parcial_1' => 'nullable|numeric|min:0|max:99.9',
        'calificaciones.*.nota_parcial_2' => 'nullable|numeric|min:0|max:99.9',
        'calificaciones.*.nota_parcial_3' => 'nullable|numeric|min:0|max:99.9',
    ];

    public function mount(Nivel $grupo)
    {
        $this->grupo = $grupo;


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

        $alumnos = $this->grupo->alumnos()
            ->with(['carrera', 'notas' => function ($query) {
                $query->where('nivel_id', $this->grupo->id_nivel);
            }])
            ->get(); 

        foreach ($alumnos as $alumno) {
            $nota = $alumno->notas->first();
            $this->calificaciones[$alumno->id_alumno] = [
                'nota_parcial_1' => $nota->nota_parcial_1 ?? null,
                'nota_parcial_2' => $nota->nota_parcial_2 ?? null,
                'nota_parcial_3' => $nota->nota_parcial_3 ?? null,
            ];
        }
   

       
        $this->fechaAsistencia = now()->format('Y-m-d'); // Pone la fecha de HOY
        $this->cargarAsistencias(); // Carga las asistencias para hoy
      
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
            ->pluck('asistencia', 'alumno_id') // Devuelve un array [alumno_id => 'A']
            ->toArray();
    }

  
    public function updatedFechaAsistencia()
    {
        $this->cargarAsistencias(); 
    }


    public function guardarAsistencia($alumnoId, $estatus)
    {
        // No se puede guardar asistencia si no hay parcial activo
        if ($this->parcialActivoNumero === 0) {
            $this->dispatch('alerta-error', mensaje: 'No hay un parcial activo para registrar asistencia.');
            return;
        }

        Asistencia::updateOrCreate(
            [
                //  BUSCAR
                'alumno_id' => $alumnoId,
                'nivel_id'  => $this->grupo->id_nivel,
                'fecha'     => $this->fechaAsistencia,
            ],
            [
                // Columnas para ACTUALIZAR o CREAR
                'parcial'     => $this->parcialActivoNumero, // Usa el número 1, 2 o 3
                'asistencia'  => $estatus, // 'A' o 'F'
            ]
        );

        // Actualiza el array local
        $this->asistenciasHoy[$alumnoId] = $estatus;

        $this->dispatch('alerta-exito', mensaje: 'Asistencia guardada.');
    }

    public function updatedCalificaciones($value, $key)
    {
        $this->validateOnly('calificaciones.' . $key);

        $parts = explode('.', $key);
        $alumnoId = $parts[0];

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

        $this->dispatch('alerta-exito', mensaje: '¡Calificación actualizada con éxito!');
    }

    public function toggleFiltroReprobados()
    {
        $this->filtroReprobados = !$this->filtroReprobados;
        $this->resetPage(); // Resetea la paginación al cambiar de filtro
    }

    // Hook para resetear la página si se cambia la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // La consulta base de alumnos
        $alumnosQuery = $this->grupo->alumnos()
            ->with(['carrera']) 
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('ap_paterno', 'like', '%' . $this->search . '%')
                        ->orWhere('ap_materno', 'like', '%' . $this->search . '%');
                });
            })
            // APLICA EL FILTRO N/A (Reprobados) 
            ->when($this->filtroReprobados && $this->modo === 'calificaciones' && $this->parcialActivo, function ($query) {
                $query->whereHas('notas', function ($subQuery) {
                    $subQuery->where('nivel_id', $this->grupo->id_nivel)
                             

                             ->where($this->parcialActivo, '>', 0) 
                             
                             ->where($this->parcialActivo, '<', 70);
                             

                });
            });

        return view('livewire.profesor.grupo-vista', [
            
            'alumnos' => $alumnosQuery->paginate(15), 
            'parcialActivo' => $this->parcialActivo 
        ]);
    }
}
