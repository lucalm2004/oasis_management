<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Discoteca extends Model
{
    protected $table = 'discotecas';
    protected $fillable = ['name', 'direccion', 'image', 'lat', 'long', 'capacidad', 'id_ciudad'];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id');
    }

    public function evento()
    {
        return $this->hasMany(Evento::class, 'id');
    } 
    public function users_discoteca()
    {
        return $this->belongsToMany(UserDiscoteca::class, 'id_discoteca');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_discoteca');
    }

    public function valoracionMedia()
    {
        // Obtener todas las valoraciones de los eventos de esta discoteca
        $valoraciones = $this->eventos()->with('valoraciones')->get()->flatMap->valoraciones;

        // Calcular la valoraciÃ³n media
        if ($valoraciones->isNotEmpty()) {
            return $valoraciones->avg('rating');
        } else {
            return 0; // Manejo de casos donde no hay valoraciones
        }
    }
  
}
