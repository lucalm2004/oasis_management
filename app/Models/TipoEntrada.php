<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEntrada extends Model
{
    protected $table = 'tipos_entradas';
    protected $fillable = ['descripcion', 'precio'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }
}
