<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de importar la clase DB si no lo has hecho aún
use Illuminate\Support\Facades\Validator;
use App\Models\discotecas;
use App\Models\User;
use App\Models\Users;
class registerGestorController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'dni_nie' => 'required|regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'discoteca' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required',
            'image' => 'required|image',
            'capacidad' => 'required|numeric|min:100|max:9999',
             
        ]);
      

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
      

        $nombreCompleto = $request->input('nombre_completo');
        $dniNie = $request->input('dni_nie');
        $email = $request->input('email');
        $password = $request->input('password');
        $discoteca = $request->input('discoteca');
        $direccion = $request->input('direccion');
        $capacidad = $request->input('capacidad');
        $ciudad = $request->input('ciudad');
        $ciudadEncontrada = DB::table('ciudades')->where('name', $ciudad)->first();
        $idCiudad = $ciudadEncontrada->id;

        $imagen = $request->file('image');
        $originalName = $imagen->getClientOriginalName();
        $imageName = time() . '_' . $originalName;
        $imagePath = 'img/discotecas';
        $imagen->move(public_path($imagePath), $imageName);

    // Hacer la solicitud a la API de Geocodificación de Google Maps para obtener las coordenadas
    $apiKey = 'AIzaSyBHnWvq4QKI7BwTKqm5vXGxLxNz01jSyiY'; // Reemplaza 'TU_API_KEY' por tu propia clave de API de Google Maps
    $formattedAddress = urlencode($direccion . ', ' . $ciudad);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$formattedAddress}&key={$apiKey}";
    $response = file_get_contents($url);
    $json = json_decode($response);
    $latitud = $json->results[0]->geometry->location->lat;
    $longitud = $json->results[0]->geometry->location->lng;

    $discotecaData = [
        'name' => $discoteca,
        'direccion' => $direccion,
        'lat' => $latitud,
        'long' => $longitud,
        'id_ciudad' => $idCiudad,
        'image' => $imageName,
        'capacidad'=> $capacidad
    ];
    
    $discotecaId = DB::table('discotecas')->insertGetId($discotecaData);
    
    // Luego, guardamos los datos en la tabla 'users'
    $userData = [
        'name' => $nombreCompleto,
        'email' => $email,
        'password' => bcrypt($password),
        'dni' => $dniNie,
        'id_rol' => 3
    ];
    // Insertamos los datos del usuario
    $userId = DB::table('users')->insertGetId($userData);

    DB::table('users_discotecas')->insert([
        'id_users' => $userId,
        'id_discoteca' => $discotecaId
    ]);
    // return redirect()->route('login');

    }

}
