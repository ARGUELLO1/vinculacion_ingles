<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoNivel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_documento';
    protected $table = 'documentos_niveles';

    protected $fillable = [
        'nivel_id',
        'tipo_doc',
        'ruta_doc',
    ];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }
}