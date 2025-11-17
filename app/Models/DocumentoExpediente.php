<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DocumentoExpediente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_documento_expediente';
    protected $table = 'documentos_expedientes';

    protected $fillable = [
        'nivel_id',
        'tipo_doc',
        'ruta_doc'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getDocumentoUrlAttribute()
    {
        return route('documentos.ver', $this->id_documento_expediente);
    }

    public function getDocumentoExisteAttribute()
    {
        return Storage::disk('expedientesAlumnos')->exists($this->ruta_doc);
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_id', 'id_nivel');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'nivel_id', 'nivel_id');
    }

    public function expediente_a(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'id_expediente');
    }
}
