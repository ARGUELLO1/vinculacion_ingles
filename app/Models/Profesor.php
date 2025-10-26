<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_profesor';
    protected $table = 'profesores';

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'edad',
        'estado_civil_id',
        'sexo',
        'calle',
        'numero',
        'colonia',
        'codigo_postal',
        'municipio_id',
        'estado',
        'rfc',
        'estatus',
        'user_id',
    ];

    protected $casts = [
        'edad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estadoCivil(): BelongsTo
    {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function niveles(): HasMany
    {
        return $this->hasMany(Nivel::class, 'profesor_id');
    }

    /**
     * Get the full name of the profesor.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->ap_paterno} {$this->ap_materno}");
    }
}