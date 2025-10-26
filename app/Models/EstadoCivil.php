<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoCivil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estado_civil';
    protected $table = 'estados_civiles';

    protected $fillable = [
        'tipo_estado_civil',
    ];

    public function profesores(): HasMany
    {
        return $this->hasMany(Profesor::class, 'estado_civil_id');
    }
}