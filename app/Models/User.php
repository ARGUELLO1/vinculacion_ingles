<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    /**
     * Relación con el modelo Alumno
     */
    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'user_id');
    }

    /**
     * Relación con el modelo Profesor
     */
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'user_id');
    }

    /**
     * Relación con el modelo Administrador
     */
    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'user_id');
    }

    /**
     * Relación con el modelo Coordinador
     */
    public function coordinador()
    {
        return $this->hasOne(Coordinador::class, 'user_id');
    }

    /**
     * Relación con el modelo Capturista
     */
    public function capturista()
    {
        return $this->hasOne(Capturista::class, 'user_id');
    }
}