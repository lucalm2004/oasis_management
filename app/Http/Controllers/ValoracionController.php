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
    $request->validate([
        'eventId' => 'required|integer', // Verifica que este campo sea el correcto
        'rating' => 'required|numeric|min:1|max:5',
        'descripcion' => 'nullable|string', // Permitir descripción opcional
    ]);

    try {
        // Crear una nueva instancia de Valoraciones con los datos validados
        $valoracion = new Valoraciones();
        $valoracion->id_evento = $request->eventId;
        $valoracion->rating = $request->rating;
        $valoracion->descripcion = $request->descripcion; // Asignar la descripción si está presente
        $valoracion->id_user = Auth::id(); // Asignar el ID del usuario autenticado

        $valoracion->save(); // Guardar la valoración en la base de datos

        return back()->with('success', 'Valoración enviada correctamente.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al enviar la valoración: ' . $e->getMessage());
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
        $event = Evento::findOrFail($idEvento); // Buscar el evento por su ID
        $reviews = $event->valoraciones()->with('user')->get(); // Obtener las reseñas del evento

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
            ->select('users.name', 'valoraciones.rating', 'valoraciones.descripcion', 'eventos.name AS evento_nombre')
            ->join('users', 'users.id', '=', 'valoraciones.id_user')
            ->join('eventos', 'eventos.id', '=', 'valoraciones.id_evento')
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