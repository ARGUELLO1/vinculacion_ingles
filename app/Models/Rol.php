<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'tipo_rol'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'rol_id', 'id_rol');
    }

    /**
     * Scope para buscar por tipo de rol
     */
    public function scopePorTipo($query, $tipoRol)
    {
        return $query->where('tipo_rol', $tipoRol);
    }

    /**
     * Método para verificar si el rol es administrador
     */
    public function esAdministrador()
    {
        return $this->tipo_rol === 'administrador';
    }

    /**
     * Método para verificar si el rol es profesor
     */
    public function esProfesor()
    {
        return $this->tipo_rol === 'profesor';
    }

    /**
     * Método para verificar si el rol es alumno
     */
    public function esAlumno()
    {
        return $this->tipo_rol === 'alumno';
    }

    /**
     * Método para verificar si el rol es capturista
     */
    public function esCapturista()
    {
        return $this->tipo_rol === 'capturista';
    }
}