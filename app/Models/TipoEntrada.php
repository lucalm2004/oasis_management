<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEntrada extends Model
{
    protected $table = 'tipo_entradas';
    protected $fillable = ['descripcion', 'precio', 'id_producto'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }
}
