<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Discoteca;
use App\Models\Evento;
use App\Models\TipoEntrada;
use App\Models\Ciudad;
use App\Models\Bonificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{

    public function index()
    {
        $todasdiscotecas = Discoteca::all();
        $user = Auth::user();

        // Verificar si hay un usuario autenticado
        $nombreUsuario = $user ? $user->name : null;

        // Pasar la variable 'nombreUsuario' a la vista
        return view('cliente.discoteca', compact('todasdiscotecas', 'nombreUsuario'));
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

    public function carrito()
    {
        // Aquí puedes obtener la información de las entradas compradas por el usuario
        // Puedes hacer esto de acuerdo a tu lógica de negocio específica

        // Por ahora, simplemente redireccionaremos al usuario a la vista del carrito
        return view('cliente.carrito');
    }

    public function bonificacion()
    {
        // Aquí puedes obtener la información de las entradas compradas por el usuario
        // Puedes hacer esto de acuerdo a tu lógica de negocio específica

        // Por ahora, simplemente redireccionaremos al usuario a la vista del carrito
        return view('cliente.bonificacion');
    }

    public function mostrardisco(Request $request)
    {
        // Obtener todos los registros de discotecas
        $discotecas = Discoteca::query();

        // Aplicar filtros si se proporcionan
        if ($request->has('ciudad')) {
            $ciudad = $request->input('ciudad');
            $discotecas->where('id_ciudad', $ciudad);
        }

        // Filtrar por nombre si se proporciona
        if ($request->has('nombre')) {
            $nombre = $request->input('nombre');
            $discotecas->where('name', 'like', '%' . $nombre . '%');
        }

        // Obtener los resultados
        $resultados = $discotecas->get();

        // Devolver los resultados como JSON
        return response()->json($resultados);
    }

    public function mostrarpuntos()
    {
        // Obtener el usuario autenticado actualmente
        $user = Auth::user();

        // Verificar si hay un usuario autenticado
        if ($user) {
            // Obtener los puntos del usuario
            $puntosUsuario = $user->puntos;

            // Devolver los puntos del usuario como respuesta JSON
            return response()->json($puntosUsuario);
        } else {
            // Si no hay usuario autenticado, devuelve un mensaje de error
            return response()->json(['error' => 'El usuario no ha iniciado sesión'], 401);
        }
    }

    public function ciudades()
    {
        // Obtener todas las ciudades disponibles desde la base de datos
        $ciudades = Ciudad::all();

        // Retornar las ciudades en formato JSON
        return response()->json($ciudades);
    }

    public function fetchBonificaciones()
    {
        $bonificaciones = Bonificacion::all();
        return response()->json($bonificaciones);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
