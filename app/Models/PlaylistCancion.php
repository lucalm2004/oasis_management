<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistCancion extends Model
{
    protected $table = 'playlists_canciones';
    protected $fillable = ['id_evento', 'id_canciones'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function cancion()
    {
        return $this->belongsTo(Cancion::class, 'id');
    }
}
