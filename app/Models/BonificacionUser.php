<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BonificacionUser extends Model
{
    protected $table = 'users_bonificaciones';
    protected $fillable = ['id_users', 'id_bonificacion'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function bonificacion()
    {
        return $this->belongsTo(Bonificacion::class, 'id_bonificacion');
    }
}
