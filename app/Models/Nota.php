<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nota extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nota';

    protected $fillable = [
        'nota_parcial_1',
        'nota_parcial_2',
        'nota_parcial_3',
    ];

    protected $casts = [
        'nota_parcial_1' => 'decimal:1',
        'nota_parcial_2' => 'decimal:1',
        'nota_parcial_3' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'nota_id');
    }

    /**
     * Calculate the promedio of the notas.
     */
    public function getPromedioAttribute(): float
    {
        return ($this->nota_parcial_1 + $this->nota_parcial_2 + $this->nota_parcial_3) / 3;
    }
}