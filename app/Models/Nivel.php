<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nivel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nivel';
    protected $table = 'niveles';

    protected $fillable = [
        'nivel',
        'nombre_grupo',
        'aula',
        'cupo_max',
        'horario',
        'profesor_id',
        'modalidad_id',
        'parcial_1',
        'parcial_2',
        'parcial_3',
        'nivel_concluido'
    ];

    protected $casts = [
        'cupo_max' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'nivel_concluido' => 'boolean',
    ];

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'profesor_id', 'id_profesor');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id_periodo');
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'nivel_id', 'id_nivel');
    }

    public function documentosExpedientes(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'nivel_id', 'id_nivel');
    }

    public function documentosNiveles(): HasMany
    {
        return $this->hasMany(DocumentoNivel::class, 'nivel_id', 'id_nivel');
    }

    public function documentosProfesores(): HasMany
    {
        return $this->hasMany(DocumentoProfesor::class, 'nivel_id', 'id_nivel');
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'nivel_id', 'id_nivel');
    }

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id', 'id_modalidad');
    }

    public function asistencia(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'nivel_id', 'id_nivel');
    }

    public function nota(): HasMany
    {
        return $this->hasMany(Nota::class, 'nivel_id', 'id_nivel');
    }

    public function expediente(): HasOne{
        return $this->hasOne(Expediente::class, 'id_nivel');
    }

    /**
     * Obtener el parcial activo actual
     */
    public function getParcialActivoAttribute(): int
    {
        if ($this->parcial_1 == 1) return 1;
        if ($this->parcial_2 == 1) return 2;
        if ($this->parcial_3 == 1) return 3;
        return 1; // Default al parcial 1
    }

    /**
     * Cambiar el parcial activo
     */
    public function cambiarParcialActivo(int $parcial): bool
    {
        try {
            // Resetear todos los parciales a 0
            $this->update([
                'parcial_1' => '0',
                'parcial_2' => '0',
                'parcial_3' => '0',
            ]);

            // Activar el parcial seleccionado (usar 1 en lugar de true)
            switch ($parcial) {
                case 1:
                    return $this->update(['parcial_1' => '1']);
                case 2:
                    return $this->update(['parcial_2' => '1']);
                case 3:
                    return $this->update(['parcial_3' => '1']);
                default:
                    return $this->update(['parcial_1' => '1']);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al cambiar parcial: " . $e->getMessage());
        }
    }

    /**
     * Verificar si un parcial está activo
     */
    public function isParcialActivo(int $parcial): bool
    {
        return match ($parcial) {
            1 => $this->parcial_1 == 1,
            2 => $this->parcial_2 == 1,
            3 => $this->parcial_3 == 1,
            default => false
        };
    }

    /**
     * Obtener el nombre del parcial activo
     */
    public function getNombreParcialActivoAttribute(): string
    {
        return match ($this->parcial_activo) {
            1 => 'Parcial 1',
            2 => 'Parcial 2',
            3 => 'Parcial 3',
            default => 'Parcial 1'
        };
    }

    /**
     * Obtener el estado de todos los parciales
     */
    public function getEstadoParcialesAttribute(): array
    {
        return [
            1 => $this->parcial_1 == 1,
            2 => $this->parcial_2 == 1,
            3 => $this->parcial_3 == 1,
        ];
    }

    /**
     * Verificar si el nivel está concluido
     */
    public function isConcluido(): bool
    {
        return $this->nivel_concluido == 1;
    }

    /**
     * Marcar el nivel como concluido
     */
    public function marcarComoConcluido(): bool
    {
        return $this->update([
            'nivel_concluido' => '1',
            'parcial_1' => '0',
            'parcial_2' => '0',
            'parcial_3' => '0' // Desactivar todos los parciales al concluir
        ]);
    }

    /**
     * Reactivar un nivel concluido
     */
    public function reactivar(): bool
    {
        return $this->update([
            'nivel_concluido' => '0',
            'parcial_1' => '1' // Activar parcial 1 por defecto al reactivar
        ]);
    }

    /**
     * Accessor para el estado del nivel
     */
    public function getEstadoNivelAttribute(): string
    {
        return $this->nivel_concluido ? 'Concluido' : 'Activo';
    }
}
