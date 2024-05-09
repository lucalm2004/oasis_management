<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
            ->select('carrito.*', 'productos.name', 'eventos.name') // Seleccionar el nombre del evento
            ->get();


        // // Obtener los productos en el carrito del usuario con la información del producto
        // $carrito = Carrito::where('id_user', $idUsuario)
        //     ->join('productos', 'carrito.id_producto', '=', 'productos.id')
        //     ->leftJoin('canciones', 'productos.id', '=', 'canciones.id_producto') // Realizar un left join con la tabla de canciones
        //     ->join('eventos', 'carrito.id_evento', '=', 'eventos.id')
        //     ->select('carrito.*', 'productos.name', 'eventos.name', 'canciones.name', 'canciones.precio') // Seleccionar el nombre del evento
        //     ->get();


        // Devolver los productos en formato JSON
        return response()->json($carrito);
    }

    public function eliminarProductoCarrito($id)
    {
        /*    dd($id); */
        // Buscar el producto en el carrito por su ID
        /* $productoEnCarrito = Carrito::find($id); */
        /*  dd($productoEnCarrito); */

        $productoEnCarrito = DB::table('carrito')
            ->where('id', $id)->delete();

        if (!$productoEnCarrito) {
            // Si el producto no se encuentra en el carrito, devolver un error
            return response()->json(['error' => 'El producto no se encuentra en el carrito'], 404);
        }

        /*  // Eliminar el producto del carrito
        $productoEnCarrito->delete(); */

        // Devolver una respuesta exitosa
        return response()->json(['success' => true], 200);
    }

    public function insertarCancion(Request $request)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            // Obtener el último ID insertado en la tabla de canciones
            $ultimoId = Cancion::max('id');
            $idUsuario = auth()->user()->id;

            // Sumar uno al último ID para obtener el nuevo ID de la canción
            $nuevoId = $ultimoId + 1;


            // Si la canción se ha insertado correctamente, insertar el producto
            $producto = new Producto();
            $producto->name = $request->nombreCancion;
            $producto->tipo = "Cancion";
            $producto->save();


            // Crear una nueva instancia del modelo de Cancion y asignar el nombre de la canción
            $cancion = new Cancion();
            $cancion->id = $nuevoId;
            $cancion->name = $request->nombreCancion;
            $cancion->duracion = null;
            $cancion->precio = 3.00;
            $cancion->artista = $request->nombreArtista;

            // Guardar la canción en la base de datos
            $cancion->save();

            // $carrito = new Carrito();
            // $carrito->precio_total = 3;
            // $carrito->id_user = $idUsuario;
            // //Añadir evento en que sonará la canción
            // $carrito->id_producto = $nuevoId;
            // $carrito->save();

            // Confirmar la transacción si todo ha ido bien
            DB::commit();

            // Devolver una respuesta JSON con un indicador de éxito y un mensaje
            return response()->json(['success' => true, 'message' => 'Canción insertada correctamente en la base de datos']);
        } catch (\Exception $e) {
            // Revertir la transacción si hay un error
            DB::rollback();

            // Devolver una respuesta JSON con un indicador de éxito falso y un mensaje de error
            return response()->json(['success' => false, 'message' => 'Hubo un problema al intentar insertar la canción en la base de datos'], 500);
        }
    }
}
