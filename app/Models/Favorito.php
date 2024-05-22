<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'favoritos';
    protected $fillable = [
        'user_id', 'discoteca_id'
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the discoteca that is favorited.
     */
    public function discoteca()
    {
        return $this->belongsTo(Discoteca::class);
    }
}
