<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoraciones;
use App\Models\Discoteca;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ValoracionController extends Controller
{

public function create($idEvento)
{
    // Buscar el evento por su ID
    $evento = Evento::findOrFail($idEvento);

    // Verificar si el evento fue encontrado
    if (!$evento) {
        abort(404); // Manejar el caso en que el evento no exista
    }

    // Mostrar la vista de valoración pasando el evento
    return view('valoracion_create', compact('evento'));
}

public function store(Request $request)
{
    try {
        $request->validate([
            'eventId' => 'required|integer',
            'rating' => 'required|numeric|min:1|max:5',
            'descripcion' => 'nullable|string',
        ]);

        // Verificar si el usuario ya ha enviado una valoración para este evento
        $existingRating = Valoraciones::where('id_evento', $request->eventId)
            ->where('id_user', Auth::id())
            ->exists();

        if ($existingRating) {
            return response()->json(['error' => 'Ya has enviado una valoración para este evento. No se permiten múltiples valoraciones por evento.'], 400);
        }

        // Crear una nueva valoración si no hay una valoración existente del usuario para este evento
        $valoracion = new Valoraciones();
        $valoracion->id_evento = $request->eventId;
        $valoracion->rating = $request->rating;
        $valoracion->descripcion = $request->descripcion;
        $valoracion->id_user = Auth::id();

        $valoracion->save();

        return response()->json(['success' => 'Valoración enviada correctamente.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al enviar la valoración: ' . $e->getMessage()], 500);
    }
}

    public function createValoracion(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'id_evento' => 'required|exists:eventos,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);
    
        // Crear la valoración en la base de datos
        try {
            Valoraciones::create([
                'event_id' => $request->id_evento,  // Asegúrate de que sea 'event_id'
                'rating' => $request->rating,
                'descripcion' => $request->descripcion,
                'id_user' => $request->id_user,
                // Puedes incluir más campos según tu modelo de Valoracion
            ]);
    
            // Obtener el ID de la discoteca asociada al evento
            $idDiscoteca = Evento::findOrFail($request->id_evento)->id_discoteca;
    
            // Redirigir a la página de eventos de la discoteca
            return redirect()->route('valoracion.eventos', ['idDiscoteca' => $idDiscoteca]);
        } catch (\Exception $e) {
            // Capturar cualquier error y manejarlo adecuadamente
            return response()->json(['error' => 'Error al enviar la valoración: ' . $e->getMessage()], 500);
        }
    }
    

public function showEventos($idDiscoteca)
{
    $discoteca = Discoteca::findOrFail($idDiscoteca);
    $eventos = $discoteca->eventos;

    return response()->json(['eventos' => $eventos]);
}
public function showResenas($idEvento)
{
    try {
        // Obtener todas las reseñas para los eventos de la discoteca
        $reviews = DB::table('valoraciones')
            ->join('users', 'valoraciones.id_user', '=', 'users.id')
            ->select('valoraciones.*', 'users.name as user_name')
            ->whereIn('valoraciones.id_evento', function($query) use ($idEvento) {
                // Subconsulta para obtener todos los eventos de la discoteca
                $query->select('id')->from('eventos')->where('id_discoteca', $idEvento);
            })
            ->get();

        // Devolver las reseñas como respuesta JSON
        return response()->json(['resenas' => $reviews]);
    } catch (\Exception $e) {
        // Capturar cualquier error y manejarlo adecuadamente
        return response()->json(['error' => 'Error al obtener las reseñas del evento: ' . $e->getMessage()], 500);
    }
}
public function showValoracionPage()
{
    try {
        // Obtener todas las discotecas con sus eventos
        $discotecas = Discoteca::with('eventos')->get();

        // Supongamos que queremos mostrar las reseñas del primer evento de la primera discoteca
        $primerEvento = $discotecas->first()->eventos->first();

        if ($primerEvento) {
            // Obtener las reseñas del evento específico
            $resenas = $primerEvento->valoraciones()->with('user')->get();
        } else {
            $resenas = [];
        }

        return view('valoracion', compact('discotecas', 'resenas'));
    } catch (\Exception $e) {
        // Capturar cualquier error y manejarlo adecuadamente
        return response()->json(['error' => 'Error al cargar la página de valoración: ' . $e->getMessage()], 500);
    }
}
public function showTopRatedUsers()
{
    try {
        $topRatedUsers = DB::table('valoraciones')
            ->select('users.name', 'valoraciones.rating', 'valoraciones.descripcion', 'eventos.name AS evento_nombre', 'discotecas.name AS discoteca_nombre')
            ->join('users', 'users.id', '=', 'valoraciones.id_user')
            ->join('eventos', 'eventos.id', '=', 'valoraciones.id_evento')
            ->join('discotecas', 'discotecas.id', '=', 'eventos.id_discoteca')
            ->orderBy('eventos.name') // Ordenar por nombre del evento
            ->orderByDesc('valoraciones.rating') // Ordenar por rating descendente
            ->limit(10) // Limitar a 10 usuarios por evento
            ->get();

        return response()->json(['topRatedUsers' => $topRatedUsers]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al obtener los usuarios con mejor valoración: ' . $e->getMessage()], 500);
    }
}


}
