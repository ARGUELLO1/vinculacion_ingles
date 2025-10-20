<?php
// app/Models/Alumno.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $primaryKey = 'id_alumno';
    public $timestamps = true;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'edad' => 'integer',
        'carrera_id' => 'integer',
        'nivel_id' => 'integer',
        'estatus_id' => 'integer',
        'user_id' => 'integer',
        'expediente_id' => 'integer',
        'nota_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Relación con Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
    }

    /**
     * Relación con Nivel
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    /**
     * Relación con Estatus
     */
    public function estatus()
    {
        return $this->belongsTo(Estatus_Alumno::class);
    }

    /**
     * Relación con Expediente
     */
    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    /**
     * Relación con Nota
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }

    /**
     * Accesor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->ap_paterno} {$this->ap_materno}";
    }

    /**
     * Scope para alumnos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estatus_id', 1); // Asumiendo que 1 es activo
    }

    /**
     * Scope por carrera
     */
    public function scopePorCarrera($query, $carrera_id)
    {
        return $query->where('carrera_id', $carrera_id);
    }
}
