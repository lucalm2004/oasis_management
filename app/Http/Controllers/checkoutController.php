<?php

namespace App\Http\Controllers;

use App\Models\discotecas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CheckoutController extends Controller
{
    public function index(Request $request) {
        $discoteca = $request->input('discoteca');
        // dd($discoteca);
        return view('cliente.checkout');
    }

    public function checkout(Request $request){
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        $discoteca = $request->input('discoteca');
// dd($discoteca);
        $precioTotal = $request->input('precioTotal');
        $precio = $precioTotal * 100;
        $nombreEvento = $request->input('nombreEvento');
        $totalEntradas = $request->input('totalEntradas');

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
                    'totalEntradas' => $totalEntradas,
                    'discoteca' => $discoteca,
                ]),
                'cancel_url'  => route('cliente.discoteca'),
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            // Manejar el error de la API de Stripe
            return back()->withErrors(['stripe_error' => $e->getMessage()]);
        }
    }

    public function success(Request $request) {

        
        $codigo = mt_rand(1000000000, 9999999999);
        $fechaHora = now()->format('Y-m-d');
        $precioTotal = $request->query('precioTotal');
        $nombreEvento = $request->query('nombreEvento');
        $totalEntradas = $request->query('totalEntradas');
        $discoteca = $request->query('discoteca');
        
        $id = DB::table('ch_channels')->where('name', $nombreEvento)->first()->id;
        $userId = Auth::id();

        DB::table('ch_channel_user')->insert([
            'channel_id' => $id,
            'user_id' => $userId
        ]);


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
