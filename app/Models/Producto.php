<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['name', 'tipo'];

    public function tipoEntradas()
    {
        return $this->hasMany(TipoEntrada::class, 'id');
    }

    public function canciones()
    {
        return $this->hasMany(Cancion::class, 'id');
    }
}
