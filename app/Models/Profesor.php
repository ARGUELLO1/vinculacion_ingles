<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';
    public $timestamps = true;

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}