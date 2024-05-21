<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use App\Models\Artista;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Session;

use App\Models\Discoteca;
use App\Models\Evento;
use App\Models\TipoEntrada;
use App\Models\Ciudad;
use App\Models\Bonificacion;
use App\Models\Cancion;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\PlaylistCancion;
use App\Models\ArtistaCancion;
use App\Models\BonificacionUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Carbon;

class PusherController extends Controller
{
    public function index()
    {
        // $iduser = Auth::user()->id;
        // Obtener el evento actual, asumiendo que tienes lógica para esto en tu modelo Evento
        // $evento = Evento::find());
    
        // Obtener el nombre de la discoteca donde está el usuario logueado
        // $nombre_discoteca_usuario = Auth::user()->discoteca->nombre;
    
        // Mandar las variables a la vista
        // return view('index', [
        //     'foto_evento' => $evento->foto,
        //     'nombre_evento' => $evento->nombre,
        //     'nombre_discoteca_usuario' => $nombre_discoteca_usuario
        // ]);
        return view('index');
    }

    public function broadcast(Request $request)
    {
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();

        return view('broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request)
    {
        return view('receive', ['message' => $request->get('message')]);
    }
}