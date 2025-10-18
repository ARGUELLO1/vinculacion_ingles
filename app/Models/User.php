<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'user';
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
        'id_rol',
        'email',
        'password'
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

    public function role()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id_rol');
    }

    /**
     * Métodos helper para verificar roles
     */
    public function esAdministrador()
    {
        return $this->role && $this->role->esAdministrador();
    }

    public function esProfesor()
    {
        return $this->role && $this->role->esProfesor();
    }

    public function esAlumno()
    {
        return $this->role && $this->role->esAlumno();
    }

    public function esCapturista()
    {
        return $this->role && $this->role->esCapturista();
    }

    /**
     * Accesor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }
}