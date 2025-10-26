<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_carrera';

    protected $fillable = [
        'nombre_carrera',
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'carrera_id');
    }
}