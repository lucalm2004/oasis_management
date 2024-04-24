<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bonificacion extends Model
{
    protected $table = 'bonificaciones';
    protected $fillable = ['name', 'descripcion', 'puntos'];

    public function puntosUsers()
    {
        return $this->hasMany(BonificacionUser::class, 'id_bonificacion');
    }
}
