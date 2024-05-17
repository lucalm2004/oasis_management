<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventos extends Model
{
    protected $table = 'eventos';
    protected $fillable = ['name', 'descripcion', 'flyer', 'fecha_inicio', 'fecha_final', 'dj', 'name_playlist', 'id_discoteca', 'capacidad', 'capacidadVip'];
    
    public function discoteca()
    {
        return $this->belongsTo(discotecas::class, 'id_discoteca');
    }

    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'id_evento');
    }

    public function playlistCanciones()
    {
        return $this->hasMany(PlaylistCancion::class, 'id_evento');
    }

    public function carrito()
    {
        return $this->hasMany(Carrito::class, 'id_evento');
    }
}