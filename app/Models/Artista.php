<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    protected $table = 'artistas';
    protected $fillable = ['name'];
   
    public function artistas_canciones()
    {
        return $this->belongsToMany(ArtistaCancion::class, 'id_artista');
    }

   
 
}
