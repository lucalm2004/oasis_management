<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorito;

class FavoritoController extends Controller
{
    public function marcarComoFavorita(Request $request)
    {
        // Obtener el ID de la discoteca desde la solicitud
        $discotecaId = $request->input('discoteca_id');

        // Verificar si el usuario está autenticado
        if (auth()->check()) {
            // Obtener el ID del usuario autenticado
            $userId = auth()->id();

            // Verificar si ya existe una entrada en la tabla de favoritos para esta discoteca y este usuario
            $favoritoExistente = Favorito::where('user_id', $userId)
                                          ->where('discoteca_id', $discotecaId)
                                          ->exists();

            // Si la discoteca ya está marcada como favorita, retornar un mensaje de error
            if ($favoritoExistente) {
                return response()->json(['success' => false, 'message' => 'Esta discoteca ya está marcada como favorita.']);
            }

            // Si la discoteca no está marcada como favorita para este usuario, crear una nueva entrada en la tabla de favoritos
            Favorito::create([
                'user_id' => $userId,
                'discoteca_id' => $discotecaId
            ]);

            // Retornar una respuesta JSON indicando el éxito de la operación
            return response()->json(['success' => true]);
        } else {
            // Si el usuario no está autenticado, retornar un mensaje de error
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión para marcar una discoteca como favorita.']);
        }
    }
}
