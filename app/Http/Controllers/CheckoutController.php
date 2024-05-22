<?php

namespace App\Http\Controllers;

use App\Models\discotecas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Artista;
use Illuminate\Support\Facades\DB;

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
use App\Models\RegistroEntrada;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $nombreCancion = $request->input('nombreCancion');
        $nombreArtista = $request->input('nombreArtista');
        // dd($nombreCancion);
        // Ahora $nombreCancion contiene el valor del parámetro 'nombreCancion' enviado en la solicitud HTTP

        // Puedes usar $nombreCancion como necesites en tu lógica de controlador

        $discoteca = $request->input('discoteca');
        // dd($discoteca); // Si necesitas también recoger el nombre de la discoteca

        return view('cliente.checkout', compact('nombreCancion'));
    }

    // public function index(Request $request)
    // {
    //     // Obtener el ID del usuario autenticado (suponiendo que estás utilizando autenticación)
    //     $userId = auth()->id();

    //     // Obtener la última canción insertada por el usuario con precio total igual a 3
    //     $ultimaCancion = Carrito::where('id_user', $userId)
    //         ->where('precio_total', 3)
    //         ->latest()
    //         ->first();

    //     // Verificar si se encontró alguna canción
    //     if ($ultimaCancion) {
    //         $cancionId = $ultimaCancion->id;

    //         // Obtener el nombre de la canción utilizando su ID
    //         $cancion = Cancion::findOrFail($cancionId);

    //         // Verificar si se encontró la canción
    //         if ($cancion) {
    //             $nombreCancion = $cancion->name;
    //             // Puedes usar $nombreCancion y $nombreArtista como necesites en tu lógica de controlador
    //         }
    //     }
    //     dd($nombreCancion);

    //     // Si necesitas también recoger el nombre de la discoteca
    //     $discoteca = $request->input('discoteca');

    //     return view('cliente.checkout', compact('nombreCancion', 'discoteca'));
    // }

    public function checkout(Request $request)
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        $discoteca = $request->input('discoteca');
        // dd($discoteca);
        $precioTotal = $request->input('precioTotal');
        $nombreCancion = $request->input('nombreCancion');
        $precio = $precioTotal * 100;
        $nombreEvento = $request->input('nombreEvento');
        $totalEntradas = $request->input('totalEntradas');
        $tipoEntrada = $request->input('tipoEntrada');
      /*   dd($tipoEntrada); */

        // dd($precio);
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'  => [
                    [
                        'price_data' => [
                            'currency'     => 'eur',
                            'product_data' => [
                                'name' => $nombreEvento,
                            ],
                            'unit_amount'  => $precio,
                        ],
                        'quantity'   => 1,
                    ],
                ],
                'mode'        => 'payment',
                'success_url' => route('success', [
                    'precioTotal' => $precioTotal,
                    'nombreEvento' => $nombreEvento,
                    'nombreCancion' => $nombreCancion,
                    'totalEntradas' => $totalEntradas,
                    'discoteca' => $discoteca,
                    'tipoEntrada' => $tipoEntrada,
                ]),
                'cancel_url'  => route('cliente.discoteca'),
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            // Manejar el error de la API de Stripe
            return back()->withErrors(['stripe_error' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        $codigo = mt_rand(1000000000, 9999999999);
        $fechaHora = now()->format('Y-m-d');
        $precioTotal = $request->query('precioTotal');
        $nombreEvento = $request->query('nombreEvento');
        $totalEntradas = $request->query('totalEntradas');
        $discoteca = $request->query('discoteca');
        $tipoEntrada = $request->query('tipoEntrada');

        // Sumar puntos de bonificación
        $userid = Auth::user()->id; // Obtener el usuario autenticado
        $user = User::findOrFail($userid);

        // Sumar 100 puntos por compra
        if ($user) {
            $puntosBonificacion = 100; // Cantidad de puntos que deseas sumar
            $user->puntos += $puntosBonificacion; // Sumar los puntos de bonificación al usuario
            $user->save(); // Guardar los cambios
        }

        // Borrar notificaciones
        DB::table('users_bonificaciones')
            ->where('id_users', $userid)
            ->delete();

        // Borrar notificaciones
        DB::table('carrito')
            ->where('id_user', $userid)
            ->delete();

       /*  // Restar capacidad normal

        $discotecaN = Discoteca::where('name', $discoteca)->first();

        if ($discotecaN) {
            // Restar la cantidad de entradas compradas de la capacidad de la discoteca
            $discotecaN->capacidad -= $totalEntradas;
            $discotecaN->save();
        } else {
            // Si la discoteca no se encuentra, puedes manejar el error aquí
            // Por ejemplo, devolver un mensaje de error o lanzar una excepción
            return response()->json(['success' => false, 'message' => 'No se encontró la discoteca especificada.']);
        }

        // Restar capacidad VIP */
        $discotecas = Discoteca::where("name", $discoteca)->first();
        /* dd($discotecas); */

        $discotecaID = $discotecas->id;
        /* dd($discotecaID); */
        $evento = Evento::where("name", $nombreEvento)->where("id_discoteca", $discotecaID)->first();
        
        $fechaActual = now();

        $RegistroEntrada = new RegistroEntrada();
        $RegistroEntrada->id = $codigo;
        $RegistroEntrada->evento_id = $evento->id;
        $RegistroEntrada->total_entradas = $totalEntradas;
        $RegistroEntrada->precio_total = $precioTotal;
        $RegistroEntrada->fecha = $fechaActual;
        $RegistroEntrada->tipo_entrada = $tipoEntrada;
        $RegistroEntrada->save();

        // Construye el enlace que deseas incluir en el cÃ³digo QR
        $enlace = url('/payment') . '?precioTotal=' . $precioTotal . '&nombreEvento=' . urlencode($nombreEvento) . '&totalEntradas=' . $totalEntradas . '&codigo=' . $codigo . '&dia=' . $fechaHora . '&discoteca=' . urlencode($discoteca);

        $qr = QrCode::size(125)
            ->style('dot')
            ->eye('circle')
            ->color(0, 0, 255)
            ->margin(1)
            ->generate($enlace); // Usar el enlace en lugar de 'Hello, World!'

        // Pasa $qr a la vista junto con otros datos
        return view('payment2', [
            'qr' => $qr,
            'precioTotal' => $precioTotal,
            'nombreEvento' => $nombreEvento,
            'totalEntradas' => $totalEntradas,
            'codigo' => $codigo,
            'fechaHora' => $fechaHora,
            'discoteca' => $discoteca,
        ]);
    }
}
