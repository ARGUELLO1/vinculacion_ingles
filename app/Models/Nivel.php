<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    protected $casts = [
        'cupo_max' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function modalidad(): BelongsTo
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }
}
