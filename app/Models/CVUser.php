<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CVUser extends Model
{
    protected $table = 'cv_users';
    protected $fillable = ['name_pdf', 'id_user'];
    
    public function user(){
    return $this->belongsTo(User::class, 'id_user');
    }
}
