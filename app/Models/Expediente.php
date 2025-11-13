<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente';

    protected $fillable = [
        'alumno_id',
        'nivel_id',
        'ruta_expediente',
        'lin_captura_t',
        'fecha_pago',
        'fecha_entrega',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'fecha_entrega' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id_alumno');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id_nivel');
    }

    public function documentosExpedientes(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'expediente_id', 'id_expediente');
    }
}
