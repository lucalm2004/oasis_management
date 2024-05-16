<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = ['name', 'email', 'password', 'google_id', 'habilitado', 'puntos', 'id_rol', 'verificado','foto'];
    
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
    public function users_discoteca()
    {
        return $this->belongsToMany(UserDiscoteca::class, 'id_users');
    }
    public function puntosUsers()
    {
        return $this->hasMany(BonificacionUser::class, 'id_users');
    }
    public function carrito()
    {
        return $this->hasMany(Carrito::class, 'id_user');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
