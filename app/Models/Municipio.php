<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_municipio';

    protected $fillable = [
        'nombre_municipio',
    ];

    public function profesores(): HasMany
    {
        return $this->hasMany(Profesor::class, 'municipio_id');
    }
}