<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscoteca extends Model
{
    protected $table = 'users_discotecas';
    protected $fillable = ['id_discoteca', 'id_users'];

    public function discoteca()
    {
        return $this->belongsTo(Discoteca::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
