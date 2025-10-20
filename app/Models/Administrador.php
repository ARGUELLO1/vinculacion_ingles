<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradores';
    protected $primaryKey = 'id_admin';
    public $timestamps = true;
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'ap_paterno',
        'ap_materno',
    ];

    /**
     * Relación con User - UN Administrador pertenece a UN User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Accesor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno);
    }

    /**
     * Scope para búsqueda por nombre
     */
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', "%{$nombre}%")
            ->orWhere('ap_paterno', 'like', "%{$nombre}%")
            ->orWhere('ap_materno', 'like', "%{$nombre}%");
    }
}