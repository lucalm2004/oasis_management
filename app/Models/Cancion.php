<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $table = 'canciones';
    protected $fillable = ['name', 'duracion', 'precio', 'id_producto'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }

    public function artistas_canciones()
    {
        return $this->belongsToMany(ArtistaCancion::class, 'id_cancion');
    }
}
