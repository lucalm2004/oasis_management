<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';
    protected $fillable = ['precio_total', 'id_user', 'id_evento', 'id_producto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }
}
