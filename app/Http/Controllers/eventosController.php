<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PlaylistCancion;
use App\Models\UserDiscoteca;
use App\Models\CVUser;
use Google\Service\AIPlatformNotebooks\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\discotecas;
use App\Mail\AcceptarSolicitudMail;
use App\Mail\RechazarSolicitudMail;
use App\Mail\EventoCamareroMail;
use App\Mail\EventoEliminadoMail;
use App\Mail\EventoModificadoMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EliminarPersonal;
use App\Models\Discoteca;

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
                    $eventos = Evento::where('id_discoteca', $idDiscoteca)->where('name', 'like', "%$valor%")->orderBy('fecha_inicio', 'asc')->get();
                }else{
                    $eventos = Evento::where('id_discoteca', $idDiscoteca)->orderBy('fecha_inicio', 'asc')->get();

                }


            return response()->json($eventos);
        }
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }


    public function borrar(Request $request) {
        $id = $request->input('id');
    
        DB::transaction(function () use ($id) {
            // Obtener el evento antes de eliminarlo
            $evento = Evento::findOrFail($id);
    
            // Obtener la discoteca relacionada con el evento
            $discoteca_correo = Discoteca::findOrFail($evento->id_discoteca);
    
            // Formatear la fecha de inicio
            $evento->fecha_inicio = date('d/m/Y H:i', strtotime($evento->fecha_inicio));
    
            // Obtener todos los camareros de la discoteca
            $camareros = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $evento->id_discoteca)
                ->where('users.id_rol', 4) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->get();

            
            $gestorID = Auth::user()->id;
        /*     dd($gestorID); */
            // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
            $channels = DB::table('ch_channels')->where('owner_id', $gestorID)->where('name', $evento->name)->first();
           
                $channelIds = $channels->id;
              /*   dd($channels); */
                 DB::table('ch_channel_user')->where('channel_id', $channelIds)->delete();
                 
                    // Eliminar mensajes del usuario
                 DB::table('ch_messages')->where('to_channel_id', $channelIds)->delete();
                 DB::table('ch_favorites')->where('favorite_id', $channelIds)->delete();

                   DB::table('ch_channels')->where('id', $channelIds)->delete();
              
    
            // Enviar correo a todos los camareros
            foreach ($camareros as $camarero) {
                Mail::to($camarero->email)->send(new EventoEliminadoMail($camarero, $discoteca_correo, $evento));
            }
    
            // Eliminar registros relacionados con el evento
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
        $capacidad = $_POST['capacidad'];
        $capacidadVip = $_POST['capacidadVip'];
        
        
        $totalCapacidad = $capacidad + $capacidadVip;

        // dd($capacidad, $capacidadVip);
        $discoteca = Discoteca::findOrFail($idDiscoteca);

        if ($discoteca->capacidad < $totalCapacidad) {
            return response()->json(['error' => 'No se pueden vender más entradas que la propia capacidad'], 422);
            
        }
        $fechaInicioExiste = Evento::where("fecha_inicio", $fechaInicio)->first();

        if ($fechaInicioExiste) {
            return response()->json(['error' => 'Ya existe un evento con estas fechas'], 422);
        }

        $evento= Evento::create([
            'name' => $nombre,
            'descripcion' => $descripcion,
            'flyer' => $imageName,
            'fecha_inicio' => $fechaInicio,
            'fecha_final' => $fechaFin,
            'dj' => $djNombre,
            'name_playlist' => $playlistNombre,
            'id_discoteca' => $idDiscoteca,
            'capacidad' => $capacidad,
            'capacidadVip' => $capacidadVip
        ]);

         // Formatear la fecha de inicio
        $evento->fecha_inicio = date('d/m/Y H:i', strtotime($evento->fecha_inicio));
        $user_correo = User::findOrFail($user->id);
      
        $discotecaiD_correo = UserDiscoteca::where("id_users", "=", $user_correo->id)->first();
   
        $discoteca_correo = Discoteca::findOrFail($discotecaiD_correo->id_discoteca);

        // Obtener todos los camareros de la discoteca
        $camareros = DB::table('users')
        ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->where('users_discotecas.id_discoteca', $idDiscoteca)
        ->where('users.id_rol', 4) // Suponiendo que el rol de camarero tiene id_rol = 4
        ->select('users.*')
        ->get();

        $gestorID = Auth::user()->id;

        foreach ($camareros as $camarero) {
            Mail::to($camarero->email)->send(new EventoCamareroMail($camarero, $discoteca_correo, $evento));
        }
        return response()->json(['success' => 'Evento creado correctamente.']);

    }
    public function update(Request $request) {
      /*  dd($request); */
        $user = $request->user();

        $idUsuario = $user->id;
       /*  dd($idUsuario); */

        $idDiscoteca = DB::table('users_discotecas')
            ->where('id_users', $idUsuario)
            ->value('id_discoteca');

            $nameEdit = $_POST['nameEdit'];
            $djEdit = $_POST['djEdit'];
            $descEdit = $_POST['descEdit'];
            $playEdit = $_POST['playEdit'];
            $inicioEdit = $_POST['inicioEdit'];
            $finalEdit = $_POST['finalEdit'];
            $capacidad = $_POST['capacidad'];
            $capacidadVip = $_POST['capacidadVip'];
            $id = $_POST['id'];
            $eventoPrevio = Evento::findOrFail($id);
            $evento = Evento::findOrFail($id);
           
            if($request->file('flyer')){
                $imagen = $request->file('flyer');
                $originalName = $imagen->getClientOriginalName();
            
                $imageName = time() . '_' . $originalName;
                $imagePath = 'img/flyer';
                $imagen->move(public_path($imagePath), $imageName);
    
            }else {
                $imageName = $evento -> flyer;
            }

            $totalCapacidad = $capacidad + $capacidadVip;

            // dd($capacidad, $capacidadVip);
            $discoteca = Discoteca::findOrFail($idDiscoteca);
    
            if ($discoteca->capacidad < $totalCapacidad) {
                return response()->json(['error' => 'No se pueden vender más entradas que la propia capacidad'], 422);
                
            }
            $inicioEdit = date("Y-m-d H:i:s", strtotime($_POST['inicioEdit'])); // Convierte el valor a un formato compatible con la base de datos

            $fechaInicioExiste = Evento::where("fecha_inicio", $inicioEdit)->where("id", "!=", $id)->first();

            if ($fechaInicioExiste) {
                return response()->json(['error' => 'Ya existe un evento con estas fechas'], 422);
            }


            
            
            Evento::where('id', $id)->update([
                'name' => $nameEdit,
                'dj' => $djEdit,
                'descripcion' => $descEdit,
                'name_playlist' => $playEdit,
                'capacidad' => $capacidad,
                'capacidadVip' => $capacidadVip,
                'fecha_inicio' => $inicioEdit,
                'flyer' => $imageName,
                'fecha_final' => $finalEdit,
            ]);
            
            

    
             // Obtener el evento actualizado
        $evento = Evento::findOrFail($id);

        $channel = DB::table('ch_channels')->where('owner_id', $idUsuario)->where('name', $eventoPrevio->name)->first();

        if ($channel) {
            // Use the query builder's update method
            DB::table('ch_channels')
                ->where('id', $channel->id)
                ->update(['name' => $nameEdit]);
        } else {
            // Handle the case where no channel is found
            return response()->json(['message' => 'Channel not found'], 404);
        }
          // Formatear la fecha de inicio
          $evento->fecha_inicio = date('d/m/Y H:i', strtotime($evento->fecha_inicio));
          $evento->fecha_final = date('d/m/Y H:i', strtotime($evento->fecha_final));

          $user_correo = User::findOrFail($idUsuario);
   
        
          $discotecaiD_correo = UserDiscoteca::where("id_users", "=", $user_correo->id)->first();
     
          $discoteca_correo = Discoteca::findOrFail($discotecaiD_correo->id_discoteca);
  
          // Obtener todos los camareros de la discoteca
          $camareros = DB::table('users')
          ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
          ->where('users_discotecas.id_discoteca', $idDiscoteca)
          ->where('users.id_rol', 4) // Suponiendo que el rol de camarero tiene id_rol = 4
          ->select('users.*')
          ->get();
  
          foreach ($camareros as $camarero) {
              Mail::to($camarero->email)->send(new EventoModificadoMail($camarero, $discoteca_correo, $evento));
          }

        return response()->json(['success' => 'Evento modificado correctamente.']);

          
            

    }

    public function canciones(Request $request)
{
    $user = $request->user();

    $idUsuario = $user->id;

    $idDiscoteca = DB::table('users_discotecas')
        ->where('id_users', $idUsuario)
        ->value('id_discoteca');

    $canciones = DB::table('artistas_canciones')
    ->join('artistas', 'artistas_canciones.id_artista', '=', 'artistas.id')
    ->join('canciones', 'artistas_canciones.id_cancion', '=', 'canciones.id')
    ->select('canciones.id AS id_cancion','canciones.name AS nombre_cancion', 'artistas.name AS nombre_artista')
    ->get();
;
    
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

public function showSolicitudes(){
    $user = Auth::user();
    $userID = $user->id;
    $userdiscoteca = UserDiscoteca::where('id_users', $userID)->first(); // Obtener el primer resultado
    $idDiscoteca = $userdiscoteca->id_discoteca; // Acceder a la propiedad id_discoteca

    $solicitudes = DB::table('users')
        ->select('users.*', 'users_discotecas.id_discoteca', 'discotecas.name AS nombre_discoteca', 'cv_users.name_pdf AS cv')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->leftJoin('discotecas', 'users_discotecas.id_discoteca', '=', 'discotecas.id')
        ->leftJoin('cv_users', 'cv_users.id_user', '=', 'users.id')
        ->where('users.verificado', '=', 0)
        ->where('users.habilitado', '=', 0)
        ->where('users.id_rol', '=', 4)
        ->where('users_discotecas.id_discoteca', '=', $idDiscoteca)
        ->get(); // Obtener los resultados

    $count = DB::table('users')
        ->select('users.*', 'users_discotecas.id_discoteca', 'discotecas.name AS nombre_discoteca', 'cv_users.name_pdf AS cv')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->leftJoin('discotecas', 'users_discotecas.id_discoteca', '=', 'discotecas.id')
        ->leftJoin('cv_users', 'cv_users.id_user', '=', 'users.id')
        ->where('users.verificado', '=', 0)
        ->where('users.habilitado', '=', 0)
        ->where('users.id_rol', '=', 4)
        ->where('users_discotecas.id_discoteca', '=', $idDiscoteca)
        ->count();

    return response()->json([
        'solicitudes' => $solicitudes,
        'count' => $count,
    ]);
   /*  return response()->json($solicitudes); */

}

/* aceptar solicitudes */

public function AceptarSolicitudes($id){
       
    try {
        DB::beginTransaction();


        // Eliminar el usuario principal si existe
        $user = User::findOrFail($id);
        $user-> habilitado = 1;
        $user-> verificado = 1;
        $user->save();
        
        // Confirmar la transacción
        DB::commit();
        $user_correo = User::findOrFail($id);
      
        $discotecaiD_correo = UserDiscoteca::where("id_users", "=", $user_correo->id)->first();
   
        $discoteca_correo = Discoteca::findOrFail($discotecaiD_correo->id_discoteca);
            
        $email = $user_correo->email;

        Mail::to($email)->send(new AcceptarSolicitudMail($user_correo, $discoteca_correo));


        return response()->json(['success' => true, 'message' => 'Camarero aceptado correctamente']);
    } catch (\Exception $e) {
     
        DB::rollBack();

        return response()->json(['success' => false, 'error' => 'Error al aceptar camarero: ' . $e->getMessage()], 500);
    }


}

/* rechazar al gestor */

public function RechazarSolicitudes($id){
    try {
        DB::beginTransaction();
        $user_correo = User::findOrFail($id);
      
        $discotecaiD_correo = UserDiscoteca::where("id_users", "=", $user_correo->id)->first();
   
        $discoteca_correo = Discoteca::findOrFail($discotecaiD_correo->id_discoteca);
            
        $email = $user_correo->email;
        Mail::to($email)->send(new RechazarSolicitudMail($user_correo, $discoteca_correo));

        // Eliminar registros relacionados de la tabla UserDiscoteca si existen
        $UserDiscotecas = UserDiscoteca::where('id_users', $id)->first();
        $UserDiscotecas->delete();

        $cvUsers =  CVUser::where('id_user', $id)->get();
        foreach($cvUsers as $cvUser){
            if($cvUser){
                $cvUser->delete();
            }

        }

        // Eliminar el usuario principal si existe
        $user = User::findOrFail($id);
        $user->delete();
        
        // Confirmar la transacción
        DB::commit();

        return response()->json(['success' => true, 'message' => 'Camarero rechazado correctamente']);
    } catch (\Exception $e) {
     
        DB::rollBack();

        return response()->json(['success' => false, 'error' => 'Error al aceptar camarero: ' . $e->getMessage()], 500);
    }

}

public function editar(Request $request)
{
    $idEvento = $request->input('id');


    $canciones = DB::table('canciones as c')
    ->select('c.*', 'artistas.name as name_artista')
    ->join('playlists_canciones as pc', 'c.id', '=', 'pc.id_canciones')
    ->join('artistas_canciones', 'artistas_canciones.id_cancion', '=', 'c.id')
    ->join('artistas', 'artistas_canciones.id_artista', '=', 'artistas.id')
    ->where('pc.id_evento', '=', $idEvento)
    ->get();



    // dd($eventos);
   
    return response()->json([
        'canciones' => $canciones,
    ]);
}

public function playlistUpdate(Request $request)
{
    $idCancion = $request->input('idCancion');
    $idEvento = $request->input('idEvento');

    // Realizar la eliminaciÃ³n en la tabla playlists_canciones
    DB::table('playlists_canciones')
        ->where('id_evento', $idEvento)
        ->where('id_canciones', $idCancion)
        ->delete();
}

public function verPersonal(Request $request){

    $user = Auth::user();
    $userID = $user->id;
    $userDiscoteca = UserDiscoteca::where("id_users", $userID)->first();
    $discotecaID = $userDiscoteca->id_discoteca;
   

    if ($request->input('busqueda')) {
        $data = $request->input('busqueda'); 
        $camareros =  DB::table('users')
        ->select('users.*')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->where('users.verificado', '=', 1)
        ->where('users.habilitado', '=', 1)
        ->where('users.id_rol', '=', 4)
        ->where('users_discotecas.id_discoteca', '=', $discotecaID)
        ->where('name', 'like', "%$data%")
        ->get();
    } else {
        $camareros =  DB::table('users')
        ->select('users.*')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->where('users.verificado', '=', 1)
        ->where('users.habilitado', '=', 1)
        ->where('users.id_rol', '=', 4)
        ->where('users_discotecas.id_discoteca', '=', $discotecaID)
        ->get();
    }
    return response()->json($camareros);


}

public function eliminarPersonal($id){

    try {
        DB::beginTransaction();

        // Eliminar registros relacionados de la tabla UserDiscoteca si existen
        $UserDiscoteca = UserDiscoteca::where('id_users', $id)->first();
        $discoteca_correo = Discoteca::where("id", $UserDiscoteca->id_discoteca)->first();
    /*     dd($discoteca_correo); */
        if($UserDiscoteca){
            $UserDiscoteca->delete();
        }

        #eliminar cv usuarios
        $cv = CVUser::where('id_user', $id)->get();
        foreach($cv as $cvs){
            if($cvs){
                $cvs->delete();
            } 
        }
        $user_correo = User::findOrFail($id);
        $email = $user_correo->email;
        Mail::to($email)->send(new EliminarPersonal($user_correo, $discoteca_correo));

        // Eliminar el usuario principal si existe
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
        }
        
        
        // Confirmar la transacción
        DB::commit();
       

        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    } catch (\Exception $e) {
     
        DB::rollBack();

        return response()->json(['success' => false, 'error' => 'Error al eliminar usuario: ' . $e->getMessage()], 500);
    }

}

public function discoteca(){
    $user = Auth::user();
    $userID = $user->id;
 
    $userdiscoteca = UserDiscoteca::where("id_users", $userID)->first();
   /*  dd($userdiscoteca); */
    $discotecaID = $userdiscoteca->id_discoteca;
/*     dd($discotecaID); */
    $discoteca = Discoteca::findOrFail($discotecaID);
    
    return response()->json($discoteca);
}

public function discotecaUpdate(Request $request){
    try {
        DB::beginTransaction();
        $id = $request->input("id");
       /*  dd($id);
 */
        $discotecaNueva = Discoteca::findOrFail($id);
        $discotecaNuevaName = $request->input('name');
        

       /*  dd($request->hasFile('imagen')); */
        if($request->file('imagen')){
            $imagen = $request->file('imagen');
            $originalName = $imagen->getClientOriginalName();
        
            $imageName = time() . '_' . $originalName;
            $imagePath = 'img/discotecas';
            $imagen->move(public_path($imagePath), $imageName);

        }else {
            $imageName = $discotecaNueva -> image;
        }
        
         
        
       
        $ciudad = $discotecaNueva->id_ciudad;
        $ciudadEncontrada = DB::table('ciudades')->where('id', $ciudad)->first();
        // Obtener latitud y longitud
        $apiKey = 'AIzaSyBHnWvq4QKI7BwTKqm5vXGxLxNz01jSyiY';
        $formattedAddress = urlencode($request->input('direccion') . ', ' . $ciudadEncontrada->name);
      /*   dd($formattedAddress); */
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$formattedAddress}&key={$apiKey}";
        $response = file_get_contents($url);
        $json = json_decode($response);
        $latitud = $json->results[0]->geometry->location->lat;
        $longitud = $json->results[0]->geometry->location->lng; 

        // Crear nueva discoteca
       
        $discotecaNueva->name = $discotecaNuevaName;
        $discotecaNueva->direccion = $request->input('direccion');
        $discotecaNueva->image = $imageName;
        $discotecaNueva->lat = $latitud;
        $discotecaNueva->long = $longitud;
        $discotecaNueva->capacidad = $request->input('capacidad');
        $discotecaNueva->save();

        DB::commit();

      

        return response()->json(['success' => 'Discoteca creada correctamente.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error al crear discoteca: ' . $e->getMessage()], 500);
    }

}


       
}
