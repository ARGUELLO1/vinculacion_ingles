<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoExpediente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_documento_expediente';
    protected $table = 'documentos_expedientes';

    protected $fillable = [
        'expediente_id',
        'nivel',
        'const_na',
        'comp_pago',
        'lin_captura',
    ];

    protected $casts = [
        'nivel' => 'integer',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}