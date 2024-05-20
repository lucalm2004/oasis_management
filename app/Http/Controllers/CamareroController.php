<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use App\Models\PlaylistCancion;
use Illuminate\Support\Facades\DB;

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
        $currentDate = now();
       /*  dd($currentDate); */
//    dd($request);
        if (!empty($filtro['busqueda']) || (!empty($filtro['fecha']) && in_array($filtro['fecha'], ['asc', 'desc']))) {
            $user = Auth::user();
    // dd($user);
        $id = $user->id;
        
            $listar_eventos = Evento::select(
                'eventos.id',
                'eventos.name',
                'eventos.descripcion',
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
                ->where('users.id', $id)
                ->where('eventos.fecha_final', '>' , $currentDate);
    
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
                'eventos.name',
                'eventos.descripcion',
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
                ->where('users.id', $id)
                ->where('eventos.fecha_final', '>' , $currentDate);
        }
    
        // Obtener los resultados de la consulta
        $resultados = $listar_eventos->get();
    
        return response()->json(['listar_eventos' => $resultados]);
    }

    public function playlist(Request $request)
    {
        $user = $request->user();
    
        if ($user) {
            $idUsuario = $user->id;
    
            $idDiscoteca = DB::table('users_discotecas')
                ->where('id_users', $idUsuario)
                ->value('id_discoteca');
            
            
            $fechaActual = now();
            $eventos = Evento::where('id_discoteca', $idDiscoteca)->where('fecha_final', '>' , $fechaActual)->get();
            
            $cancionesPorEvento = [];
            foreach ($eventos as $evento) {
                $cancionesPorEvento[$evento->id] = PlaylistCancion::where('id_evento', $evento->id)->count();
            }
    
            return response()->json([
                'eventos' => $eventos,
                'cancionesPorEvento' => $cancionesPorEvento
            ]);
        }
    
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }
    public function editar(Request $request){
        $idEvento = $request->input('id');


        $canciones = DB::table('canciones as c')
        ->select('c.*', 'artistas.name as name_artista')
        ->join('playlists_canciones as pc', 'c.id', '=', 'pc.id_canciones')
        ->join('artistas_canciones', 'artistas_canciones.id_cancion', '=', 'c.id')
        ->join('artistas', 'artistas_canciones.id_artista', '=', 'artistas.id')
        ->where('pc.id_evento', '=', $idEvento)
        ->get();



        // dd($eventos);
    
        return response()->json([
            'canciones' => $canciones,
        ]);
    }
    
}