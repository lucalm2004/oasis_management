<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Carbon;

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
        $discoteca = Discoteca::findOrFail($evento->id_discoteca);
        $ndiscoteca = $discoteca->name;
        // Obtener los tipos de entrada disponibles
        $tiposEntradas = TipoEntrada::all();

        // Cargar la vista y pasar los datos del evento y los tipos de entrada disponibles
        return view('cliente.entradas', compact('evento', 'tiposEntradas', 'ndiscoteca'));
    }

    public function mostrarDetallesEvento($id)
    {
        $evento = Evento::findOrFail($id);
        // $id = $evento->id;

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
        $user = Auth::user();

        // Verificar si hay un usuario autenticado
        $nombreUsuario = $user ? $user->name : null;

        // Pasar la variable 'nombreUsuario' a la vista
        return view('cliente.bonificacion', compact('nombreUsuario'));
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
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Obtener todos los registros de eventos relacionados con la discoteca
        $eventos = Evento::where('id_discoteca', $id_discoteca);

        // Filtrar por nombre del evento si se proporciona
        if ($request->has('nombre')) {
            $nombreEvento = $request->input('nombre');
            $eventos->where('name', 'like', '%' . $nombreEvento . '%');
        }

        // Filtrar por día de inicio si se proporciona y si el evento aún no ha pasado
        if ($request->has('diaInicio')) {
            $diaInicio = $request->input('diaInicio');
            $eventos->where('fecha_inicio', '>=', $fecha_actual)->whereDate('fecha_inicio', $diaInicio);
        } else {
            // Si no se proporciona el día de inicio, filtrar solo los eventos que aún no han pasado
            $eventos->where('fecha_inicio', '>=', $fecha_actual);
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
        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Obtener las bonificaciones que aún no han sido canjeadas por el usuario logueado
        $bonificaciones = DB::table('bonificaciones')
            ->leftJoin('users_bonificaciones', function ($join) use ($usuario) {
                $join->on('bonificaciones.id', '=', 'users_bonificaciones.id_bonificacion')
                    ->where('users_bonificaciones.id_users', '=', $usuario->id);
            })
            ->whereNull('users_bonificaciones.id_bonificacion')
            ->select('bonificaciones.*')
            ->get();

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
        // Obtener las entradas seleccionadas del formulario
        $entradasSeleccionadas = $request->input('entradas');
        $idUsuario = $request->user()->id;
        $idEvento = $request->input('id_evento');

        // Recorrer las entradas seleccionadas
        foreach ($entradasSeleccionadas as $tipoEntradaId) {
            // Consultar el producto asociado al tipo de entrada
            $producto = Producto::find($tipoEntradaId);
            if ($producto) {
                // dd($tipoEntradaId);

                // Establecer el precio total según el ID del producto
                $precioTotal = 0;
                if ($tipoEntradaId === '1') {
                    $precioTotal = 10;
                } elseif ($tipoEntradaId === '2') {
                    $precioTotal = 15;
                } elseif ($tipoEntradaId === '3') {
                    $precioTotal = 125;
                }

                // Crear una nueva entrada en el carrito para cada tipo de entrada seleccionada
                $carrito = new Carrito();
                // Aquí puedes obtener la bonificación y el precio total de alguna manera
                $carrito->bonificacion = null;
                $carrito->precio_total = $precioTotal;
                $carrito->id_user = $idUsuario;
                $carrito->id_evento = $idEvento;
                $carrito->id_producto = $producto->id;
                $carrito->save();
            } else {
                // Si no se encuentra el producto asociado, devuelve un error
                return Response::json(['error' => 'No se encontró el producto asociado a la entrada'], 404);
            }
        }

        // Devolver una respuesta con un mensaje de éxito para SweetAlert
        return Response::json(['success' => true], 200);
    }


    public function obtenerCarrito()
    {
        // Obtener el ID del usuario autenticado
        $idUsuario = auth()->user()->id;

        // Obtener los productos en el carrito del usuario con la información del producto
        $carrito = Carrito::where('id_user', $idUsuario)
            ->join('productos', 'carrito.id_producto', '=', 'productos.id')
            ->join('eventos', 'carrito.id_evento', '=', 'eventos.id') // Realizar un join con la tabla de eventos
            ->leftJoin('canciones', 'productos.id', '=', 'canciones.id') // Realizar un left join con la tabla de canciones
            ->select('carrito.*', 'productos.name as nombre_producto', 'eventos.name as nombre_evento', 'canciones.name as nombre_cancion') // Seleccionar el nombre del evento y el nombre de la canción
            ->get();

        // Devolver los productos en formato JSON
        return response()->json($carrito);
    }

    public function obtenerBonificaciones()
    {
        // Obtener el ID del usuario autenticado
        $idUsuario = auth()->user()->id;

        // Obtener las bonificaciones relacionadas con el usuario
        $bonificaciones = DB::table('users_bonificaciones')
            ->join('bonificaciones', 'users_bonificaciones.id_bonificacion', '=', 'bonificaciones.id')
            ->where('users_bonificaciones.id_users', $idUsuario)
            ->select('bonificaciones.name')
            ->get();

        // Devolver los productos en formato JSON
        return response()->json($bonificaciones);
    }

    public function eliminarProductoCarrito($id)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            $productoEnCarrito = Carrito::findOrFail($id);
            $producto_id = $productoEnCarrito->id_producto;
            // $evento_id = $productoEnCarrito->id_evento;

            $productoEnCarrito->delete();

            // Si el ID del producto es mayor que 3, eliminar todos los registros asociados al producto
            if ($producto_id > 3) {
                $relacion = DB::table('artistas_canciones')
                    ->where('id_cancion', $producto_id)
                    ->first();

                if ($relacion) {
                    $artista_id = $relacion->id_artista;

                    DB::table('artistas_canciones')
                        ->where('id_cancion', $producto_id)
                        ->delete();

                    $artista = Artista::findOrFail($artista_id);
                    $artista->delete();
                }

                $relacion1 = DB::table('playlists_canciones')
                    ->where('id_canciones', $producto_id)
                    ->first();

                if ($relacion1) {
                    DB::table('playlists_canciones')
                        ->where('id_canciones', $producto_id)
                        ->delete();
                }

                $cancion = Cancion::findOrFail($producto_id);
                $cancion->delete();

                $producto = Producto::findOrFail($producto_id);
                $producto->delete();
            }

            // Confirmar la transacción
            DB::commit();

            // Devolver una respuesta exitosa
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();

            // Devolver una respuesta con indicador de error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un problema al eliminar el producto del carrito: ' . $e->getMessage()
            ], 500);
        }
    }



    public function insertarCancion(Request $request)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            // Obtener el ID del usuario autenticado
            $idUsuario = auth()->user()->id;

            // Obtener el último ID insertado en la tabla de carrito
            $ultimaEntrada = Carrito::orderBy('created_at', 'desc')->first();
            if ($ultimaEntrada) {
                // Si se encontró una última entrada en el carrito, obtener el ID del evento
                $eventoId = $ultimaEntrada->id_evento;
            } else {
                return response()->json(['success' => false, 'message' => 'No se encontraron entradas en el carrito.'], 404);
            }

            $nombreCancion = $request->nombreCancion;
            $nombreArtista = $request->nombreArtista;

            // Validar canción repetida
            $nombreRepetido = DB::table('canciones')
                ->join('playlists_canciones', 'canciones.id', '=', 'playlists_canciones.id_canciones')
                ->where('canciones.name', $nombreCancion)
                ->where('playlists_canciones.id_evento', $eventoId)
                ->exists();

            if ($nombreRepetido) {
                return response()->json(['error' => true, 'message' => 'El nombre de la canción ya sonará en esta playlist'], 400);
            }

            // Obtener la fecha de inicio del evento en formato de marca de tiempo Unix
            $fecha_inicio_evento = strtotime(Evento::where('id', $eventoId)->value('fecha_inicio'));

            // Obtener la fecha y hora actuales en formato de marca de tiempo Unix
            $timestamp_actual = time();

            // Verificar si quedan menos de 24 horas para el evento
            if ($fecha_inicio_evento - $timestamp_actual < 24 * 3600) { // 24 horas en segundos
                return response()->json(['success' => false, 'message' => 'No puedes insertar canción si quedan menos de 24 horas para que empiece el evento'], 500);
            }

            // Verificar si el producto ya existe
            $producto = Producto::where('name', $nombreCancion)->first();

            if (!$producto) {
                // Producto no existe, crear uno nuevo
                $producto = new Producto();
                $producto->name = $nombreCancion;
                $producto->tipo = "Cancion";
                $producto->save();

                // Crear la canción solo si el producto no existía
                $cancion = new Cancion();
                $cancion->id = $producto->id;
                $cancion->name = $nombreCancion;
                $cancion->duracion = null;
                $cancion->precio = 3.00;
                $cancion->artista = $nombreArtista;
                $cancion->save();
            }

            // Carrito
            $carrito = new Carrito();
            $carrito->precio_total = 3;
            $carrito->id_user = $idUsuario;
            $carrito->id_evento = $eventoId;
            $carrito->id_producto = $producto->id;
            $carrito->save();

            // Relación evento canción
            $relacion = new PlaylistCancion();
            $relacion->id_canciones = $producto->id;
            $relacion->id_evento = $eventoId;
            $relacion->save();

            // Verificar si el artista ya existe
            $artista = Artista::where('name', $nombreArtista)->first();

            if (!$artista) {
                // Artista no existe, crear uno nuevo
                $artista = new Artista();
                $artista->name = $nombreArtista;
                $artista->save();
            }

            // Relación artista canción
            $cancionartista = new ArtistaCancion();
            $cancionartista->id_artista = $artista->id;
            $cancionartista->id_cancion = $producto->id;
            $cancionartista->save();

            // Confirmar la transacción si todo ha ido bien
            DB::commit();

            // Devolver una respuesta JSON con un indicador de éxito y un mensaje
            return response()->json(['success' => true, 'message' => 'Canción insertada correctamente en la base de datos']);
        } catch (\Exception $e) {
            // Revertir la transacción si hay un error
            DB::rollback();

            // Devolver una respuesta JSON con un indicador de éxito falso y un mensaje de error
            return response()->json(['success' => false, 'message' => 'Hubo un problema al intentar insertar la canción en la base de datos', 'error' => $e->getMessage()], 500);
        }
    }


    public function canjearBonificacion(Request $request)
    {
        // Obtener el ID de la bonificación a canjear desde la solicitud
        $idBonificacion = $request->input('idBonificacion');
        // dd($idBonificacion);
        $idUsuario = auth()->user()->id;

        // Obtener la bonificación desde la base de datos
        $bonificacion = Bonificacion::find($idBonificacion);

        // Verificar si la bonificación existe
        if (!$bonificacion) {
            return response()->json(['success' => false, 'message' => 'La bonificación no existe.']);
        }

        // Obtener el usuario actual
        // $usuario = Auth::user();
        $usuario = User::find($idUsuario);

        // Verificar si el usuario tiene suficientes puntos para canjear la bonificación
        if ($usuario->puntos < $bonificacion->puntos) {
            return response()->json(['success' => false, 'message' => 'No tienes suficientes puntos para canjear esta bonificación.']);
        }

        // Restar los puntos de la bonificación al usuario
        $usuario->puntos -= $bonificacion->puntos;
        $usuario->save();

        $relacion = new BonificacionUser();
        $relacion->id_users = $idUsuario;
        $relacion->id_bonificacion = $idBonificacion;
        $relacion->save();


        // Devolver una respuesta de éxito
        return response()->json(['success' => true, 'message' => 'La bonificación ha sido canjeada correctamente.']);
    }

    public function obtenerCancionesSeleccionadas()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener las canciones que están en el carrito del usuario
        $cancionesSeleccionadas = DB::table('carrito')
            ->join('canciones', 'carrito.id_producto', '=', 'canciones.id')
            ->where('carrito.id_user', $user->id)
            ->select('canciones.*')
            ->get();

        // dd($cancionesSeleccionadas);

        // Retornar las canciones en formato JSON
        return response()->json($cancionesSeleccionadas);
    }


    public function obtenerBonificacionesCanjeadas()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener las bonificaciones canjeadas del usuario desde la tabla de relación
        $bonificacionesCanjeadas = DB::table('users_bonificaciones')
            ->join('bonificaciones', 'users_bonificaciones.id_bonificacion', '=', 'bonificaciones.id')
            ->where('users_bonificaciones.id_users', $user->id)
            ->select('bonificaciones.*')
            ->get();

        // Retornar las bonificaciones en formato JSON
        return response()->json($bonificacionesCanjeadas);
    }
}
