<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';
    protected $fillable = ['rating', 'reseña', 'id_evento', 'id_user'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
