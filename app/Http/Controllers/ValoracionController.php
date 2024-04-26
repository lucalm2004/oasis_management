<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoracion;
use App\Models\Evento;
use App\Models\Discoteca;
use Illuminate\Support\Facades\Auth;

class ValoracionController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva valoración.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function create(Evento $evento)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Debes iniciar sesión para valorar.');
        }

        return view('valoracion.create', compact('evento'));
    }

    /**
     * Almacena una nueva valoración en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'reseña' => 'nullable|string|max:255',
            'id_evento' => 'required|exists:eventos,id',
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Crear una nueva valoración
        $valoracion = new Valoracion([
            'rating' => $request->input('rating'),
            'reseña' => $request->input('reseña'),
            'id_evento' => $request->input('id_evento'),
            'id_user' => $user->id,
        ]);

        // Guardar la valoración en la base de datos
        $valoracion->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('evento.show', $request->input('id_evento'))->with('success', '¡Gracias por tu valoración!');
    }
    
    public function showValoracionPage()
    {
        $discotecas = Discoteca::all();
        // dd($discotecas);
        return view('valoracion', compact('discotecas'));
    }
    
    
}
