<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente';

    protected $fillable = [
        'nivel',
        'lin_captura',
        'soli_aspirante',
        'acta_nac',
        'comp_estu',
        'ine',
        'comp_pago',
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

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'expediente_id');
    }

    public function documentosExpedientes(): HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'expediente_id');
    }
}