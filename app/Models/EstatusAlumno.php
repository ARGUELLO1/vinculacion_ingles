<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusAlumno extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estatus_alumno';
    protected $table = 'estatus_alumnos';

    protected $fillable = [
        'tipo_estatus_alumno',
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'estatus_id');
    }
}