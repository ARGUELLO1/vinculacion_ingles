<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumno extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_alumno';

    protected $fillable = [
        'matricula',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'edad',
        'carrera_id',
        'telefono',
        'sexo',
        'nivel_id',
        'estatus_id',
        'user_id',
        'expediente_id',
        'nota_id',
    ];

    protected $casts = [
        'edad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }

    public function estatus(): BelongsTo
    {
        return $this->belongsTo(EstatusAlumno::class, 'estatus_id');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }

    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class, 'nota_id');
    }

    /**
     * Get the full name of the alumno.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->ap_paterno} {$this->ap_materno}");
    }
}