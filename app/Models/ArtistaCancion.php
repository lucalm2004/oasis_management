<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ArtistaCancion extends Model
{
    protected $table = 'artistas_canciones';
    protected $fillable = ['id_artista', 'id_cancion'];

    public function artista()
    {
        return $this->belongsTo(Artista::class, 'id');
    }

    public function cancion()
    {
        return $this->belongsTo(Cancion::class, 'id');
    }
}
