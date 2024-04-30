<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Evento; 
use Illuminate\Http\Request;

class CamareroController extends Controller
{
    public function listar_eventos()
    {
      $sql = 'SELECT * FROM users_discotecas
             INNER JOIN tbl_discotecas d ON d.id = id_discoteca
            INNER JOIN tbl_users u ON u.id = .id_users';
            
      $listar_eventos = DB::select($sql);  

    //   return response()->json($listar_eventos);
    return response()->json(['listar_eventos' => $listar_eventos]);
    }
} 
