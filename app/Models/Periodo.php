<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_periodo';

    protected $fillable = [
        'periodo',
    ];

    public function niveles(): HasMany
    {
        return $this->hasMany(Nivel::class, 'periodo_id');
    }
}