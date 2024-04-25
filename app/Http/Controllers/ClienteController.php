<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Discoteca;
use App\Models\Evento;
use App\Models\TipoEntrada;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $todasdiscotecas = Discoteca::all();
        $puntosUsuario = 0;
        
        // Obtener los puntos del usuario con ID 2
        $usuarioId = 2;
        $puntosUsuario = DB::table('users')->where('id', $usuarioId)->value('puntos');
    
        return view('cliente.discoteca', compact('todasdiscotecas', 'puntosUsuario'));
    }

    public function eventos($id)
    {
        $discoteca = Discoteca::findOrFail($id);
        $eventos = Evento::where('id_discoteca', $id)->get();
        return view('cliente.eventos', compact('discoteca', 'eventos'));
    }

    public function mostrar($id)
    {
        // Obtener el evento correspondiente al ID proporcionado
        $evento = Evento::findOrFail($id);

        // Obtener los tipos de entrada disponibles
        $tiposEntradas = TipoEntrada::all();

        // Cargar la vista y pasar los datos del evento y los tipos de entrada disponibles
        return view('cliente.entradas', compact('evento', 'tiposEntradas'));
    }
}