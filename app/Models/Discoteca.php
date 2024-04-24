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


  
}
