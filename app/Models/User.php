<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rol_id',
        'email',
        'password',
        'name' // Agregar esto si existe en tu tabla
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con la tabla Roles (CORREGIDO)
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id_rol');
    }

    // Método para verificar rol (CORREGIDO)
    public function hasRol($role)
    {
        if (is_string($role)) {
            return $this->rol && $this->rol->tipo_rol === $role;
        }

        return $this->rol && $this->rol->id_rol === $role->id_rol;
    }

    // Método alternativo más simple
    public function esAlumno()
    {
        return $this->rol && $this->rol->tipo_rol === 'alumno';
    }

    public function esProfesor()
    {
        return $this->rol && $this->rol->tipo_rol === 'profesor';
    }

    public function esAdministrador()
    {
        return $this->rol && $this->rol->tipo_rol === 'administrador';
    }

    // Relación con Alumno
    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'user_id', 'id_user');
    }

    // Relación con Administrador
    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'user_id', 'id_user');
    }

    // Relación con Profesor
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'user_id', 'id_user');
    }

    /**
     * Accesor para nombre completo (ACTUALIZADO)
     */
    public function getNombreCompletoAttribute()
    {
        // Si los datos están en las tablas relacionadas
        if ($this->esAlumno() && $this->alumno) {
            return $this->alumno->nombre . ' ' . $this->alumno->ap_paterno . ' ' . $this->alumno->ap_materno;
        }

        if ($this->esProfesor() && $this->profesor) {
            return $this->profesor->nombre . ' ' . $this->profesor->ap_paterno . ' ' . $this->profesor->ap_materno;
        }

        if ($this->esAdministrador() && $this->administrador) {
            return $this->administrador->nombre . ' ' . $this->administrador->ap_paterno . ' ' . $this->administrador->ap_materno;
        }

        return $this->name ?? 'Usuario';
    }

    /**
     * Obtener los datos del perfil según el rol
     */
    public function perfil()
    {
        if ($this->esAlumno()) {
            return $this->alumno;
        } elseif ($this->esProfesor()) {
            return $this->profesor;
        } elseif ($this->esAdministrador()) {
            return $this->administrador;
        }

        return null;
    }

    public function esCapturista()
    {
        return $this->rol && $this->rol->tipo_rol === 'capturista';
    }
}
