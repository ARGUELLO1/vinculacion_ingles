<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';
    protected $primaryKey = 'id_carrera';

    protected $fillable = [
        'nombre_carrera',
    ];

    // Relación con alumnos
    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'carrera_id', 'id_carrera');
    }

    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre_carrera;
    }
}
