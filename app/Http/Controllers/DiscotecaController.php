<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discoteca;
use App\Models\Evento;

class DiscotecaController extends Controller
{
    /**
     * Obtener eventos por ID de discoteca.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEventosByDiscoteca($id)
    {
        try {
            // Buscar la discoteca por su ID
            $discoteca = Discoteca::findOrFail($id);

            // Obtener los eventos asociados a esta discoteca
            $eventos = Evento::where('id_discoteca', $id)->get();

            return response()->json(['discoteca' => $discoteca, 'eventos' => $eventos], 200);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['error' => 'Error al obtener eventos: ' . $e->getMessage()], 500);
        }
    }
    public function obtenerDiscotecasFavoritas()
    {
        // Lógica para obtener las discotecas favoritas del usuario
        $usuario = auth()->user(); // Suponiendo que estás utilizando autenticación de usuarios
        
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        
        $discotecasFavoritas = $usuario->discotecasFavoritas; // Suponiendo que tienes una relación definida en tu modelo de usuario
        
        return response()->json($discotecasFavoritas);
    }
}
