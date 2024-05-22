<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroEntrada extends Model
{
    protected $table = 'registro_entradas';
    protected $fillable = ['name', 'evento_id', 'total_entradas', 'precio_total', 'fecha'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }
}
