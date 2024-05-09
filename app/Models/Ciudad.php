<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $fillable = ['name'];

    public function discotecas()
    {
        return $this->hasMany(Discoteca::class, 'id_ciudad'); 
    }
}
