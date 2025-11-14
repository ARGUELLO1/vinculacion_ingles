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
        'grupo',
        'aula',
        'profesor_id',
        'cupo_max',
        'periodo_id',
        'modalidad',
        'horario',
        'nivel_concluido',
    ];

    protected $casts = [
        'cupo_max' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'nivel_concluido' => 'boolean', 
    ];

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'nivel_id');
    }

    public function documentosNiveles(): HasMany
    {
        return $this->hasMany(DocumentoNivel::class, 'nivel_id');
    }

    public function documentosProfesores(): HasMany
    {
        return $this->hasMany(DocumentoProfesor::class, 'nivel_id');
    }
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id', 'id_modalidad');
    }
       public function scopeActivos(Builder $query): void
    {
        $query->where('nivel_concluido', false); // o ->where('nivel_concluido', 0)
    }
        public function scopeConcluidos(Builder $query): void
    {
        $query->where('nivel_concluido', true); // o ->where('nivel_concluido', 1)
    }

    public function expediente(): HasOne{
        return $this->hasOne(Expediente::class, 'id_nivel');
    }
}
