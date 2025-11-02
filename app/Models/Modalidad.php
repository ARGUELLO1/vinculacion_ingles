<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;

    protected $table = 'modalidades';

    protected $primaryKey = 'id_modalidad';

    protected $fillable = [
        'tipo_modalidad'
    ];

    // Relación con niveles (una modalidad puede tener muchos niveles)
    public function niveles()
    {
        return $this->hasMany(Nivel::class, 'modalidad_id', 'id_modalidad');
    }
}