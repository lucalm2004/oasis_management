<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Discoteca;
use App\Models\Evento;
use App\Models\TipoEntrada;
use App\Models\Ciudad;
use App\Models\Bonificacion;
use App\Models\Cancion;
use App\Models\Carrito;
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

    public function mostrarDetallesEvento($id)
    {
        $evento = Evento::findOrFail($id);

        return response()->json($evento);
    }

    public function mostrarTiposEntrada($id)
    {
        $tiposEntrada = TipoEntrada::all();

        return response()->json($tiposEntrada);
    }



    public function carrito()
    {

        return view('cliente.carrito');
    }

    public function bonificacion()
    {

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

    public function mostrareventos(Request $request, $id_discoteca)
    {
        // Obtener todos los registros de eventos relacionados con la discoteca
        $eventos = Evento::where('id_discoteca', $id_discoteca);

        // Filtrar por nombre del evento si se proporciona
        if ($request->has('nombre')) {
            $nombreEvento = $request->input('nombre');
            $eventos->where('name', 'like', '%' . $nombreEvento . '%');
        }

        // Filtrar por día de inicio si se proporciona
        if ($request->has('diaInicio')) {
            $diaInicio = $request->input('diaInicio');
            $eventos->whereDate('fecha_inicio', $diaInicio);
        }

        // Obtener los resultados
        $resultados = $eventos->get();

        // Devolver los resultados como JSON
        return response()->json($resultados);
    }


    public function mostrarDetallesDiscoteca($id)
    {
        $discoteca = Discoteca::findOrFail($id);

        return response()->json($discoteca);
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

    public function mostrarCancionesEvento($id_evento)
    {
        // Obtener los IDs de las canciones asociadas al evento
        $ids_canciones = DB::table('playlists_canciones')
            ->where('id_evento', $id_evento)
            ->pluck('id_canciones');

        // Obtener los detalles de las canciones asociadas
        $canciones = Cancion::whereIn('id', $ids_canciones)->get();

        return response()->json($canciones);
    }

    public function insertarEnCarrito(Request $request)
    {
        // Obtener los datos de la solicitud
        $data = $request->json()->all();
        $entradasSeleccionadas = $data['entradas'];
        $idUsuario = $request->user()->id; // Suponiendo que estés utilizando autenticación y el usuario esté autenticado
        $idEvento = $request->input('id_evento'); // Obtener el ID del evento de la solicitud
    
        // Decodificar las entradas JSON
        $entradasJSON = json_decode($entradasSeleccionadas, true);
    
        // Iterar sobre las entradas seleccionadas e insertarlas en la tabla carrito
        foreach ($entradasJSON as $tipoEntradaId => $cantidad) {
            $carrito = new Carrito();
            $carrito->tipo_entrada_id = $tipoEntradaId;
            $carrito->cantidad = $cantidad;
            $carrito->bonificacion = $request-> null; // Obtener bonificación de la solicitud
            $carrito->precio_total = $request-> null; // Obtener precio total de la solicitud
            $carrito->id_user = $idUsuario;
            $carrito->id_evento = $idEvento;
            $carrito->id_producto = $request->input('id_producto'); // Obtener ID del producto de la solicitud
            $carrito->save();
        }
    
        // Puedes retornar una respuesta de éxito si lo deseas
        return response()->json(['message' => 'Entradas insertadas en el carrito con éxito'], 200);
    }
    
}
