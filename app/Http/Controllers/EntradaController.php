<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    public function revisarEntrada(Request $request)
    {
        $codigo = $request->input('codigo');
        $discoteca = $request->input('discoteca');
        $precioTotal = $request->input('precioTotal');
        $nombreEvento = $request->input('nombreEvento');
        $totalEntradas = $request->input('totalEntradas');
        $dia = $request->input('dia');

        // Primero, realiza la búsqueda en la tabla 'evento'

        $discotecaid = DB::table('discotecas')
            ->where('name', $discoteca)
            ->first();

        $evento = DB::table('eventos')
            ->where('name', $nombreEvento)
            ->where('id_discoteca', $discotecaid->id) 
            ->first();

        // Si el evento no existe, retorna un mensaje de error
        if (!$evento) {
            return response()->json([
                'success' => false,
                'message' => 'El evento no existe.'
            ]);
        }

        // Luego, realiza la búsqueda en la tabla 'registro_entradas'
        $entrada = DB::table('registro_entradas')
            ->where('id', $codigo)
            ->where('evento_id', $evento->id)
            ->where('precio_total', $precioTotal)
            ->where('total_entradas', $totalEntradas)
            ->where('fecha', $dia)
            ->first();

        if ($entrada) {
            return response()->json([
                'success' => true,
                'message' => 'La entrada está correcta.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Los detalles de la entrada no son correctos.'
            ]);
        }
    }
}