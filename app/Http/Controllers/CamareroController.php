<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use App\Models\Discoteca;

class CamareroController extends Controller
{
    
    public function camarero()
    {
        // Obtener todas las discotecas disponibles
        $discotecas = Discoteca::all();
    
        // Retornar la vista 'listar_eventos/camarero' pasando la variable $discotecas
        return view('listar_eventos.camarero', compact('discotecas'));
    }
    
    public function listar_eventos(Request $request)
    {
        $filtro = $request->except('_token');
    
        // Inicializar la variable $listar_eventos
        $listar_eventos = null;
//    dd($request);
        if (!empty($filtro['busqueda']) || (!empty($filtro['fecha']) && in_array($filtro['fecha'], ['asc', 'desc']))) {
            $user = Auth::user();
    // dd($user);
        $id = $user->id;
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
                ->where('users.id', $id);
    
            if (!empty($filtro['busqueda'])) {
                $listar_eventos->where('eventos.name', 'like', '%' . $filtro['busqueda'] . '%');
            }
    
            if (!empty($filtro['fecha']) && in_array($filtro['fecha'], ['asc', 'desc'])) {
                $listar_eventos->orderBy('eventos.fecha_inicio', $filtro['fecha']);
            }
        } else {
            // dd('saa');
            // Si no hay bÃºsqueda ni fecha especificada, obtener todos los eventos
            $user = Auth::user();
            // dd($user);
                $id = $user->id;
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
                ->where('users.id', $id);
        }
    
        // Obtener los resultados de la consulta
        $resultados = $listar_eventos->get();
    
        return response()->json(['listar_eventos' => $resultados]);
    }
    
}