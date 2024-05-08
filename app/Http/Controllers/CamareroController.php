<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Evento;

class CamareroController extends Controller
{
    public function camarero() {
           return view('listar_eventos/camarero');
    }
    public function listar_eventos(Request $request)
    {
        $filtro = $request->except('_token');
    
        // Inicializar la variable $listar_eventos
        $listar_eventos = null;
//    dd($request);
        if (!empty($filtro['busqueda']) || (!empty($filtro['fecha']) && in_array($filtro['fecha'], ['asc', 'desc']))) {
            $listar_eventos = Evento::select(
                'eventos.id',
                'eventos.name AS nombre_evento',
                'eventos.descripcion AS descripcion_evento',
                'eventos.flyer',
                'eventos.fecha_inicio',
                'eventos.fecha_final',
                'eventos.dj',
                'eventos.name_playlist',
                'discotecas.name AS nombre_discoteca'
            )
                ->join('discotecas', 'eventos.id_discoteca', '=', 'discotecas.id')
                ->join('users_discotecas', 'discotecas.id', '=', 'users_discotecas.id_discoteca')
                ->join('users', 'users_discotecas.id_users', '=', 'users.id')
                ->where('users.id', 6);
    
            if (!empty($filtro['busqueda'])) {
                $listar_eventos->where('eventos.name', 'like', '%' . $filtro['busqueda'] . '%');
            }
    
            if (!empty($filtro['fecha']) && in_array($filtro['fecha'], ['asc', 'desc'])) {
                $listar_eventos->orderBy('eventos.fecha_inicio', $filtro['fecha']);
            }
        } else {
            // dd('saa');
            // Si no hay búsqueda ni fecha especificada, obtener todos los eventos
            $listar_eventos = Evento::select(
                'eventos.id',
                'eventos.name AS nombre_evento',
                'eventos.descripcion AS descripcion_evento',
                'eventos.flyer',
                'eventos.fecha_inicio',
                'eventos.fecha_final',
                'eventos.dj',
                'eventos.name_playlist',
                'discotecas.name AS nombre_discoteca'
            )
                ->join('discotecas', 'eventos.id_discoteca', '=', 'discotecas.id')
                ->join('users_discotecas', 'discotecas.id', '=', 'users_discotecas.id_discoteca')
                ->join('users', 'users_discotecas.id_users', '=', 'users.id')
                ->where('users.id', 6);
        }
    
        // Obtener los resultados de la consulta
        $resultados = $listar_eventos->get();
    
        return response()->json(['listar_eventos' => $resultados]);
    }
    
}
