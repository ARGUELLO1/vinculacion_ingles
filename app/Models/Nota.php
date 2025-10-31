<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nota';

    protected $fillable = [
        'nota_parcial_1',
        'nota_parcial_2',
        'nota_parcial_3',
        'alumno_id',  // <-- AÑADIR ESTA LÍNEA
        'nivel_id',    // <-- AÑADIR ESTA LÍNEA
    ];
    protected $casts = [
        'nota_parcial_1' => 'decimal:1',
        'nota_parcial_2' => 'decimal:1',
        'nota_parcial_3' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function alumno(): BelongsTo
    {
        // Una nota pertenece a un alumno
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id_alumno');
    }
    public function nivel(): BelongsTo
    {
        // Una nota pertenece a un nivel (grupo)
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id_nivel');
    }

    /**
     * Calculate the promedio of the notas.
     */
    public function getPromedioAttribute(): float
    {
        return ($this->nota_parcial_1 + $this->nota_parcial_2 + $this->nota_parcial_3) / 3;
    }
}
