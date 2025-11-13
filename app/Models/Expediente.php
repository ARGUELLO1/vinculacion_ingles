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

    public function alumnos(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function documentosExpedientes(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'id_expediente');
    }
}