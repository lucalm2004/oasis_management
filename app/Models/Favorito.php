<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
 

protected $table = 'favoritos';
protected $fillable = ['user_id', 'discoteca_id'];

   
     

  public function user(){
      return $this->belongsTo(User::class);}

 
     

  public function discoteca(){
      return $this->belongsTo(Discoteca::class);}
}