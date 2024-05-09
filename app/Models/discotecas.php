<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class discotecas extends Model
{
    protected $table = 'discotecas';
    protected $fillable = ['name', 'direccion', 'image', 'lat', 'long', 'capacidad', 'id_ciudad'];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad');
    }

    public function evento()
    {
        return $this->hasMany(eventos::class, 'id_discoteca');
    } 
    public function users_discoteca()
{
    return $this->hasMany(UserDiscoteca::class, 'id');
}



}