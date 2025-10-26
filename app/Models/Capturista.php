<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Capturista extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_capturista';

    protected $fillable = [
        'user_id',
        'nombre',
        'ap_paterno',
        'ap_materno',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the full name of the capturista.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->ap_paterno} {$this->ap_materno}");
    }
}