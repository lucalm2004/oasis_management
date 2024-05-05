<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PlaylistCancion;
use Google\Service\AIPlatformNotebooks\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $idUsuario = $user->id;
            $valor = $request->input('valor');

            $idDiscoteca = DB::table('users_discotecas')
                ->where('id_users', $idUsuario)
                ->value('id_discoteca');

                if($valor !== 'undefined'){
                    // dd($valor);
                    $eventos = Evento::where('id_discoteca', $idDiscoteca)->where('name', 'like', "%$valor%")->get();
                }else{
                    $eventos = Evento::where('id_discoteca', $idDiscoteca)->get();

                }


            return response()->json($eventos);
        }
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }


    public function borrar(Request $request) {
        $id = $_POST['id'];
        DB::transaction(function () use ($id) {
            DB::table('playlists_canciones')->where('id_evento', $id)->delete();
    
            Evento::where('id', $id)->delete();
        });
            return response()->json(['message' => 'Evento eliminado correctamente'], 200);
          
            

    }
    public function new(Request $request) {
        $user = $request->user();

        $idUsuario = $user->id;

        $idDiscoteca = DB::table('users_discotecas')
            ->where('id_users', $idUsuario)
            ->value('id_discoteca');

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen = $request->file('fotoNombre');
            //   dd($imagen); 
                $originalName = $imagen->getClientOriginalName();
               /*  dd($originalName);  */
                $imageName = time() . '_' . $originalName;
                $imagePath = 'img/flyer';
                $imagen->move(public_path($imagePath), $imageName);
        // dd($foto);
        $fechaInicio = $_POST['fechaInicio'];
        $djNombre = $_POST['djNombre'];
        $fechaFin = $_POST['fechaFin'];
        $playlistNombre = $_POST['playlistNombre'];

        Evento::create([
            'name' => $nombre,
            'descripcion' => $descripcion,
            'flyer' => $imageName,
            'fecha_inicio' => $fechaInicio,
            'fecha_final' => $fechaFin,
            'dj' => $djNombre,
            'name_playlist' => $playlistNombre,
            'id_discoteca' => $idDiscoteca,
        ]);

    }
    public function update(Request $request) {
        $user = $request->user();

        $idUsuario = $user->id;

        $idDiscoteca = DB::table('users_discotecas')
            ->where('id_users', $idUsuario)
            ->value('id_discoteca');

            $nameEdit = $_POST['nameEdit'];
            $djEdit = $_POST['djEdit'];
            $descEdit = $_POST['descEdit'];
            $playEdit = $_POST['playEdit'];
            $inicioEdit = $_POST['inicioEdit'];
            $finalEdit = $_POST['finalEdit'];
            $id = $_POST['id'];
            
            Evento::where('id', $id)->update([
                'name' => $nameEdit,
                'dj' => $djEdit,
                'descripcion' => $descEdit,
                'name_playlist' => $playEdit,
                'fecha_inicio' => $inicioEdit,
                'fecha_final' => $finalEdit,
            ]);
            

    }

    public function canciones(Request $request)
{
    $user = $request->user();

    $idUsuario = $user->id;

    $idDiscoteca = DB::table('users_discotecas')
        ->where('id_users', $idUsuario)
        ->value('id_discoteca');

    $canciones = DB::table('canciones')->get();
    
    $eventos = DB::table('eventos')->where('id_discoteca', $idDiscoteca)->get();
    // dd($eventos);
   
    return response()->json([
        'canciones' => $canciones,
        'eventos' => $eventos,
    ]);
}
public function playlist(Request $request)
{
    $user = $request->user();

    if ($user) {
        $idUsuario = $user->id;

        $idDiscoteca = DB::table('users_discotecas')
            ->where('id_users', $idUsuario)
            ->value('id_discoteca');

        $eventos = Evento::where('id_discoteca', $idDiscoteca)->get();
        
        $cancionesPorEvento = [];
        foreach ($eventos as $evento) {
            $cancionesPorEvento[$evento->id] = PlaylistCancion::where('id_evento', $evento->id)->count();
        }

        return response()->json([
            'eventos' => $eventos,
            'cancionesPorEvento' => $cancionesPorEvento
        ]);
    }

    return response()->json(['error' => 'Usuario no autenticado'], 401);
}

public function cancionUpdate(Request $request)
{
    $idCancion = $request->input('idCancion');
    $idEvento = $request->input('idEvento');

    // Verificar si ya existe un registro para esta canción y este evento
    $existente = PlaylistCancion::where('id_evento', $idEvento)
                                  ->where('id_canciones', $idCancion)
                                  ->exists();

    if (!$existente) {
        // No existe, se puede crear el nuevo registro
        PlaylistCancion::create([
            'id_evento' => $idEvento,
            'id_canciones' => $idCancion,
        ]);

        return response()->json(['message' => 'Canción agregada al evento correctamente'], 200);
    } else {
        // Ya existe, no se puede agregar de nuevo
        return response()->json(['error' => 'La canción ya está presente en este evento'], 422);
    }
}
       
}
