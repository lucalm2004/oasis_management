<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de importar la clase DB si no lo has hecho aún
use Illuminate\Support\Facades\Validator;

class registerCamareroController extends Controller
{
    public function index(Request $request){
     
    /*     dd($request); */
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'dni_nie' => 'required|regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'discoteca' => 'required',
            'cv' => 'required|file|mimes:pdf', // Hacer el campo 'cv' opcional
        ]);
       

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
        

        $nombreCompleto = $request->input('nombre_completo');
        $dniNie = $request->input('dni_nie');
        $email = $request->input('email');
        $password = $request->input('password');
        // Procesamiento del nombre de la discoteca
        $discoteca = $request->input('discoteca');
        // Extraer solo el nombre de la discoteca
        $discotecaName = explode(' - ', $discoteca)[0];
        $idDiscoteca = DB::table('discotecas')
        ->select('id')
        ->where('name', $discotecaName)
        ->first();
   /*      dd($idDiscoteca); */
      /*   dd($discoteca); */
  /*     dd($request->file('cv')); */
       
       /*  dd($pdf);
        */
        $pdf = $request->file('cv');
        $originalName = $pdf->getClientOriginalName();
      
        $imageName = time() . '_' . $originalName;
        $imagePath = 'doc/cv';
        $pdf->move(public_path($imagePath), $imageName);

        // Luego, guardamos los datos en la tabla 'users'
        $userData = [
            'name' => $nombreCompleto,
            'email' => $email,
            'password' => bcrypt($password),
            'dni' => $dniNie,
            'id_rol' => 4
        ];
        // Insertamos los datos del usuario
        $userId = DB::table('users')->insertGetId($userData);

        DB::table('users_discotecas')->insert([
            'id_users' => $userId,
            'id_discoteca' =>  $idDiscoteca->id
        ]);


        /* insertar dtos en la tabla del pdf */
        DB::table('cv_users')->insert([
            'name_pdf' => $imageName,
            'id_user' =>  $userId
        ]); 


       /*  return redirect()->route('login'); */

    }

}

