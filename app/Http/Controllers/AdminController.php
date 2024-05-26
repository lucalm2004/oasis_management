<?php

namespace App\Http\Controllers;

use App\Models\Bonificacion;
use App\Models\Rol;
use App\Models\User;
use App\Models\UserDiscoteca;
use App\Models\discotecas;
use App\Models\BonificacionUser;
use App\Models\Carrito;
use App\Models\Valoracion;
use App\Models\Ciudad;
use App\Models\CVUser;
use App\Models\eventos;
use App\Models\PlaylistCancion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
   use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClienteRegisterMail;
use App\Mail\CambiarEstadorMail;
use App\Mail\EliminarMail;
use App\Mail\EditarMail;
use App\Mail\EventoEliminadoMail;
use App\Mail\AcceptarSolicitudMail;
use App\Mail\RechazarSolicitudMail;
use App\Mail\DiscotecaEliminadaMail;
use App\Mail\DiscotecaModificadaMail;
use App\Models\Artista;
use App\Models\ArtistaCancion;
use App\Models\Discoteca;
use App\Models\Cancion;
use App\Models\Producto;
use App\Models\RegistroEntrada;

class AdminController extends Controller
{
    /* CRUD DISCOTECAS */

    /* ver usuarios */
   
    public function showCrudUsers(Request $request)
    {
          $query = User::query();
          $user = Auth::user();
          $userID = $user->id;
          // Verificar si se proporcionó un filtro de búsqueda
          if ($request->input('busqueda')) {
              $data = $request->input('busqueda');
              $query
                  ->orWhere('email  ', 'like', "%$data%");
          }
  
          // Verificar si se proporcionó un filtro de rol
          if ($request->input('rol')) {
              $rolId = $request->input('rol');
              $query->where('id_rol', $rolId);
          }
           // Excluir los usuarios con verificado = 0
            $query->where('verificado', '=', 1);
            $query->where('id', '!=', 2);
            $query->where('id', '!=', $userID);
            
          // Obtener los usuarios con la relación de rol cargada
          $users = $query->with('rol')->get();
  
          return response()->json($users);
    }

    /* obtener roles */
    public function showRoles()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }

    /* obtener discotecas*/

    public function showDiscotecas(){
        $discotecas = discotecas::all();
        return response()->json($discotecas);

    }

    /* cambiar estado del usuario */
    public function cambiarEstado($id)
    {
        try {
        
            DB::beginTransaction();
 
            $user = User::lockForUpdate()->findOrFail($id);

            $user->habilitado = $user->habilitado == 1 ? 0 : 1;
            $user->save();
            

            DB::commit();
            $user_correo = User::findOrFail($user->id);
            $email = $user_correo->email;
            Mail::to($email)->send(new CambiarEstadorMail($user_correo));

          
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
           
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /* obetener datos editar usuario */

    public function editUsers($id){
        $users = User::select('users.*', 'users_discotecas.id_discoteca')
            ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
            ->where('users.id', $id)
            ->first();
        return response()->json($users);
    }

    /* actualizar usuario */

    public function actualizarUsers($id, Request $request){
        try {
            DB::beginTransaction();

        // Obtener el usuario a actualizar
        $user = User::findOrFail($id);

        // Validar que el correo electrónico no esté duplicado
        $email = $request->input('email');
        $existingUser = User::where('email', $email)->where('id', '!=', $id)->first();
        if ($existingUser) {
            return response()->json(['error' => 'El correo electrónico ya está en uso por otro usuario'], 422);
        }

        
        $dni = $request->input('dni');
        if ($dni) {
            $existingUser = User::where('DNI', $dni)->where('id', '!=', $id)->first();
            if ($existingUser) {
                return response()->json(['error' => 'El DNI ya está en uso por otro usuario'], 422);
            }
        }

        // Actualizar los datos del usuario
        $user->name = $request->input('nombre');
        $user->email = $email;
        $user->id_rol = $request->input('rol');
        $user->DNI = $dni;

        // Verificar y actualizar la contraseña si se proporciona
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Guardar los cambios en la base de datos
        $user->save();
        
        

        $discotecaID = $request->input("discoteca");

        
        
        
        if ($discotecaID !== null) {// Eliminar registros relacionados de la tabla BonificacionUser si existen
            $BonificacionUsers = BonificacionUser::where('id_users', $id)->get();
            foreach($BonificacionUsers as $BonificacionUser){
                if($BonificacionUser){
                    $BonificacionUser->delete();
                }

            }
            
            // Eliminar registros relacionados de la tabla Carrito si existen
            $Carrito = Carrito::where('id_user', $id)->first();
            if($Carrito){
                $Carrito->delete();
            }
    
            // Eliminar registros relacionados de la tabla Valoracion si existen
            $Valoraciones = Valoracion::where('id_user', $id)->get();
            foreach($Valoraciones as $Valoracion){
                if($Valoracion){
                    $Valoracion->delete();
                } 
            }

            #eliminar cv usuarios
            $cv = CVUser::where('id_user', $id)->get();
            foreach($cv as $cvs){
                if($cvs){
                    $cvs->delete();
                } 
            }
         
                // Eliminar mensajes del usuario
                DB::table('ch_messages')->where('from_id', $id)->delete();

                // Eliminar favoritos del usuario
                DB::table('ch_favorites')->where('user_id', $id)->delete();

                
                DB::table('ch_channel_user')->where('user_id', $id)->delete();


        

 
             $RegistrosEntradas = RegistroEntrada::where("id_user", $id)->get();
             foreach($RegistrosEntradas as $RegistrosEntrada){
                 if($RegistrosEntrada){
                     $RegistrosEntrada->delete();
                 } 
             }

            if ($request->input('rol') == 3) {
                // Buscar un gestor diferente al usuario que se está actualizando en la misma discoteca
                $existingGestor = DB::table('users')
                ->select('users.*')
                ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users.id_rol', '=', 3)
                ->where('users.id', '!=', $id)
                ->where('users_discotecas.id_discoteca', '=', $discotecaID)
                ->first();

                // Si existe un gestor diferente, no se permite la asignación
                if ($existingGestor) {
                return response()->json(['error' => 'Ya existe un gestor diferente en esta discoteca'], 422);
                }
                
                $Usersdiscoteca = UserDiscoteca::where('id_users', '=', $id)->first();
                if ($Usersdiscoteca) {
                    $Usersdiscoteca->id_discoteca = $discotecaID;
                    $Usersdiscoteca->save();
                }else {
                    $UsersdiscotecaNueva = new UserDiscoteca();
                    $UsersdiscotecaNueva->id_users = $id;
                    $UsersdiscotecaNueva->id_discoteca = $discotecaID;
                    $UsersdiscotecaNueva->save();
                }
          
                
            }elseif ($request->input('rol') == 4) {
                
                
                $Usersdiscoteca = UserDiscoteca::where('id_users', '=', $id)->first();
                
                if ($Usersdiscoteca) {
                    $Usersdiscoteca->id_discoteca = $discotecaID;
                    $Usersdiscoteca->save();
                }else {
                    $UsersdiscotecaNueva = new UserDiscoteca();
                    $UsersdiscotecaNueva->id_users = $id;
                    $UsersdiscotecaNueva->id_discoteca = $discotecaID;
                    $UsersdiscotecaNueva->save();
                }
          
            }else{
               /*  dd("hola"); */
                $UserDiscoteca = UserDiscoteca::where("id_users", $id)->first();
                $UserDiscoteca->delete();

            }
           
        }else {
            /* dd("hola"); */
           $UserDiscoteca = UserDiscoteca::where("id_users", $id)->first();
           $UserDiscoteca->delete();
            /* dd($UserDiscoteca); */
            // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
            // Verificar si hay canales asociados al usuario y establecer owner_id como null condicionalmente
   
            
          
            
        }
        $user_correo = User::findOrFail($id);
        /*  dd($user_correo); */
        $rol_correo = Rol::findOrFail($user_correo->id_rol);
        $discotecaiD_correo = UserDiscoteca::where("id_users", "=", $user_correo->id)->first();
        if ($discotecaiD_correo) {
            $discoteca_correo = Discoteca::findOrFail($discotecaiD_correo->id_discoteca);
        } else {
            $discoteca_correo = null;
        }
         $email = $user_correo->email;
         Mail::to($email)->send(new EditarMail($user_correo, $discoteca_correo, $rol_correo));

        // Confirmar la transacción
        DB::commit();

    
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar usuario: ' . $e->getMessage()], 500);
        }
    }

    /* eliminar usuario */

    public function EliminarUsers($id){
        try {
            DB::beginTransaction();
    
            // Eliminar registros relacionados de la tabla UserDiscoteca si existen
            $UserDiscoteca = UserDiscoteca::where('id_users', $id)->first();
            if($UserDiscoteca){
                $UserDiscoteca->delete();
            }
    
            // Eliminar registros relacionados de la tabla BonificacionUser si existen
            $BonificacionUsers = BonificacionUser::where('id_users', $id)->get();
            foreach($BonificacionUsers as $BonificacionUser){
                if($BonificacionUser){
                    $BonificacionUser->delete();
                }

            }
            
    
            // Eliminar registros relacionados de la tabla Carrito si existen
            $Carrito = Carrito::where('id_user', $id)->first();
            if($Carrito){
                $Carrito->delete();
            }
    
            // Eliminar registros relacionados de la tabla Valoracion si existen
            $Valoraciones = Valoracion::where('id_user', $id)->get();
            foreach($Valoraciones as $Valoracion){
                if($Valoracion){
                    $Valoracion->delete();
                } 
            }

            #eliminar cv usuarios
            $cv = CVUser::where('id_user', $id)->get();
            foreach($cv as $cvs){
                if($cvs){
                    $cvs->delete();
                } 
            }
            // Eliminar mensajes del usuario
            DB::table('ch_messages')->where('from_id', $id)->delete();

            // Eliminar favoritos del usuario
            DB::table('ch_favorites')->where('user_id', $id)->delete();
          
            
            DB::table('ch_channel_user')->where('user_id', $id)->delete();
          

             // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
            $channels = DB::table('ch_channels')->where('owner_id', $id)->get();
            if ($channels->isNotEmpty()) {
                $channelIds = $channels->pluck('id');
                DB::table('ch_channel_user')->whereIn('channel_id', $channelIds)->delete();
                DB::table('ch_channels')->whereIn('id', $channelIds)->delete();
            }

            

            $RegistrosEntradas = RegistroEntrada::where("id_user", $id)->get();
            foreach($RegistrosEntradas as $RegistrosEntrada){
                if($RegistrosEntrada){
                    $RegistrosEntrada->delete();
                } 
            }

            $user_correo = User::findOrFail($id);
            $email = $user_correo->email;
            Mail::to($email)->send(new EliminarMail($user_correo));
    
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

    /* crear un usuario nuevo */

    public function storeUser(Request $request){
        $names = $request->input('nombres');
        $email = $request->input('email');
        $password = $request->input('password');
        $dni = $request->input('dni');
        $rol = $request->input('rol');
        try {
            DB::beginTransaction();
    
            // Validar si ya existe un usuario con el mismo correo electrónico
            $existingUser = User::where('email', $email)->first();
    
            // Si el usuario ya existe, retorna un mensaje de error
            if ($existingUser) {
                return response()->json(['error' => 'Ya existe este usuario'], 422);
            } 

            if ($dni) {
                $existingUser = User::where('DNI', $dni)->first();
                if ($existingUser) {
                    return response()->json(['error' => 'El DNI ya está en uso por otro usuario'], 422);
                }
            }
    
            // Si el usuario no existe, procede con la creación del nuevo usuario
            $user = new User();
            $user->name = $names;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->DNI = $dni;
            $user->id_rol = $rol;
            $user->habilitado= 1;
            $user->verificado= 1;
            $user->save();

           

            $discoteca = $request->input('discoteca');
            if ($discoteca !== null) {
                $gestorExistente = DB::table('users')
                ->select('users.*')
                ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users.id_rol', '=', 3)
                ->where('users_discotecas.id_discoteca', '=', $discoteca)
                ->first();

                if ($gestorExistente) {
                    return response()->json(['error' => 'La discoteca ya tiene un gestor asignado'], 422);
                }

              
                $userdiscoteca =  new UserDiscoteca();
                $userdiscoteca->id_users = $user ->id;
                $userdiscoteca->id_discoteca = $discoteca;
                $userdiscoteca->save();
            }
               
            DB::commit();
            $user_correo = User::findOrFail($user->id);
            $email = $user_correo->email;
            Mail::to($email)->send(new ClienteRegisterMail($user_correo));
    
            return response()->json(['success' => 'Usuario creado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al crear usuario: ' . $e->getMessage()], 500);
        }

    }

    /* Ver Solicitudes  */
    public function showSolicitudes(){
        
        $solicitudes = DB::table('users')
        ->select('users.*', 'users_discotecas.id_discoteca', 'discotecas.name AS nombre_discoteca')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->leftJoin('discotecas', 'users_discotecas.id_discoteca', '=', 'discotecas.id')
        ->where('users.verificado', '=', 0)
        ->where('users.habilitado', '=', 0)
        ->where('users.id_rol', '=', 3)
        ->get();
        $count = DB::table('users')
        ->leftJoin('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
        ->leftJoin('discotecas', 'users_discotecas.id_discoteca', '=', 'discotecas.id')
        ->where('users.verificado', '=', 0)
        ->where('users.habilitado', '=', 0)
        ->where('users.id_rol', '=', 3)
        ->count();
        /* dd($count); */

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

    
            return response()->json(['success' => true, 'message' => 'Gestor aceptado correctamente']);
        } catch (\Exception $e) {
         
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al aceptar gestor: ' . $e->getMessage()], 500);
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

            discotecas::where('id', $UserDiscotecas->id_discoteca)->delete();
             
            
            // Eliminar el usuario principal si existe
            $user = User::findOrFail($id);
           
            $user->delete();
            
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Gestor rechazado correctamente']);
        } catch (\Exception $e) {
         
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al aceptar gestor: ' . $e->getMessage()], 500);
        }

    }

    /* CRUD DISCOTECAS */

    /* ver discotecas con filtros*/
    public function showCrudDiscotecas(Request $request){
        $query = DB::table('discotecas as d')
            ->join('users_discotecas as du', 'd.id', '=', 'du.id_discoteca')
            ->join('users as u', 'du.id_users', '=', 'u.id')
            ->join('roles as r', 'u.id_rol', '=', 'r.id')
            ->join('ciudades as c', 'd.id_ciudad', '=', 'c.id')
            ->select('d.*', 'u.name as nombre_usuario', 'c.name as nombre_ciudad')
            ->where('r.id', '=', 3);
    
        // Verificar si se proporcionó un filtro de búsqueda
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda');
            $query->where('d.name', 'like', "%$data%");
        }
    
        // Verificar si se proporcionó un filtro de ciudad
        if ($request->input('ciudad')) {
            $ciudadId = $request->input('ciudad');
            $query->where('d.id_ciudad', $ciudadId);
        }
    
        // Ordenar por el ID de la discoteca
        $query->orderBy('d.id');
    
        $discotecas = $query->get();
    
        return response()->json($discotecas);
    }

    /* obtener lista ciudades para desplegables */
    public function showCiudades(){
        $cuidades = Ciudad::all();
        return response()->json($cuidades);

    }

    public function EliminarDiscotecas($id){
        try {
          
            // Obtener la discoteca relacionada con el evento
            $discoteca_correo = Discoteca::findOrFail($id);

    
            // Obtener todos los camareros de la discoteca
            $camareros = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $id)
                ->whereIn('users.id_rol', [3, 4]) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->get();
                $gestor = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $id)
                ->where('users.id_rol', 3) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->first();
                $gestorID = $gestor->id;
                /* dd($gestor); */
            
 
                // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
                $channels = DB::table('ch_channels')->where('owner_id', $gestorID)->get();
                if ($channels->isNotEmpty()) {
                 $channelIds = $channels->pluck('id');
                 DB::table('ch_channel_user')->whereIn('channel_id', $channelIds)->delete();
                 
                    // Eliminar mensajes del usuario
                 DB::table('ch_messages')->where('to_channel_id', $channelIds)->delete();
                 DB::table('ch_favorites')->where('favorite_id', $channelIds)->delete();

                   DB::table('ch_channels')->whereIn('id', $channelIds)->delete();
                }
                
 
    
    
            // Enviar correo a todos los camareros
            foreach ($camareros as $camarero) {
                Mail::to($camarero->email)->send(new DiscotecaEliminadaMail($camarero, $discoteca_correo));
            }

    
            // Obtener todos los eventos asociados a la discoteca
            $eventos = eventos::where('id_discoteca', $id)->get();
    
            // Eliminar las playlists, carritos y valoraciones asociadas a cada eventos
            foreach ($eventos as $evento) {
                $playlists = PlaylistCancion::where('id_evento', $evento->id)->get();
                foreach ($playlists as $playlist) {
                    if ($playlist) {
                        $playlist->delete();
                    }

                }
                
                $carritos = Carrito::where('id_evento', $evento->id)->get();
                foreach ($carritos as $carrito) {
                    if ($carrito) {
                        $carrito->delete();
                    }

                }

                $valoraciones = Valoracion::where('id_evento', $evento->id)->get();
                foreach ($valoraciones as $valoracion) {
                    if ($valoracion) {
                        $valoracion->delete();
                    }


                }

                $registroEntradas = RegistroEntrada::where('evento_id', $evento->id)->get();
                foreach ($registroEntradas as $registroEntrada) {
                    if ($registroEntrada) {
                        $registroEntrada->delete();
                    }

                }
                
                $evento->delete();

                
            }
    
            // Eliminar registros relacionados de la tabla UserDiscoteca si existen
            $UserDiscotecas = UserDiscoteca::where('id_discoteca', $id)->get();
            foreach ($UserDiscotecas as $UserDiscoteca) {
                if($UserDiscoteca){
                    $UserDiscoteca->delete();
                }

            }
        
    
            // Eliminar la discoteca principal si existe
            $discoteca = discotecas::findOrFail($id);
            if ($discoteca) {
                $discoteca->delete();
            }

            
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Discoteca eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar discoteca: ' . $e->getMessage()], 500);
        }
    }

    /* insertar una nueva discoteca */

    public function storeDiscoteca(Request $request){
        try {
            DB::beginTransaction();
    
            $discotecaNuevaName = $request->input('nombre');
         /*    dd($discotecaNuevaName);  */
    
            // Validar si la discoteca ya existe
            $discotecaExistente = discotecas::where('name', $discotecaNuevaName)->where('name', $request->input('ciudad'))->first();
            if ($discotecaExistente) {
                return response()->json(['error' => 'Ya existe esta discoteca'], 422);
            }
    
            // Obtener y guardar la imagen
          
                $imagen = $request->file('imagen');
             /*    dd($imagen); */
                $originalName = $imagen->getClientOriginalName();
               /*  dd($originalName);  */
                $imageName = time() . '_' . $originalName;
                $imagePath = 'img/discotecas';
                $imagen->move(public_path($imagePath), $imageName);
           
            $ciudad = $request->input('ciudad');
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
            $discotecaNueva = new discotecas();
            $discotecaNueva->name = $discotecaNuevaName;
            $discotecaNueva->direccion = $request->input('direccion');
            $discotecaNueva->image = $imageName;
            $discotecaNueva->lat = $latitud;
            $discotecaNueva->long = $longitud;
            $discotecaNueva->capacidad = $request->input('capacidad');
            $discotecaNueva->id_ciudad = $request->input('ciudad');
            $discotecaNueva->save();
    
            DB::commit();
    
            return response()->json(['success' => 'Discoteca creada correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear discoteca: ' . $e->getMessage()], 500);
        }
    }

    public function editDiscoteca($id){
        $discoteca = discotecas::findOrFail($id);
        return response()->json($discoteca);

    }

    /* Actualizar discoteca */

    public function actualizarDiscoteca($id, Request $request){
        try {
            DB::beginTransaction();

            $discotecaNueva = discotecas::findOrFail($id);
            $discotecaNuevaName = $request->input('nombre');
            // Verificar si existe una discoteca con el mismo nombre en la misma ciudad
                $discotecaExistente = discotecas::where('name', $discotecaNuevaName)
                ->where('id_ciudad', $request->input('ciudad'))
                ->where('id', '!=', $id)
                ->first();

            if ($discotecaExistente) {
                return response()->json(['error' => 'Ya existe una discoteca con el mismo nombre en esta ciudad'], 422);
            }
    
         
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
            
             
            
           
            $ciudad = $request->input('ciudad');
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
            $discotecaNueva->id_ciudad = $request->input('ciudad');
            $discotecaNueva->save();
    
            DB::commit();

            $user_correo = DB::table('users')
            ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
            ->where('users_discotecas.id_discoteca', $id)
            ->where('users.id_rol', 3) // Suponiendo que el rol de camarero tiene id_rol = 4
            ->select('users.*')
            ->first();

            $email = $user_correo->email;
            $discoteca_correo = Discoteca::findOrFail($id);
            $ciudad_correo = Ciudad::findOrfail($discoteca_correo->id_ciudad);
            Mail::to($email)->send(new DiscotecaModificadaMail($user_correo, $discoteca_correo, $ciudad_correo));
    
            return response()->json(['success' => 'Discoteca creada correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear discoteca: ' . $e->getMessage()], 500);
        }

    }


    /* CRUD BONIFICACIONES */

    /* mostrar bonificaciones con filtros */
    public function showCrudBonificaciones(Request $request){
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda'); 
            $bonificaciones = Bonificacion::where('name', 'like', "%$data%")->get();
        } else {
            $bonificaciones = Bonificacion::all();
        }
        return response()->json($bonificaciones);

    }

    /* eliminar una bonificación */
    public function EliminarBonificaciones($id){
        try {
            DB::beginTransaction();
    
            // Obtener todos las bonificaciones canjeadas por los usuarios
            $bonificacionesUsers = BonificacionUser::where('id_bonificacion', $id)->get();
    
            // Eliminar las playlists, carritos y valoraciones asociadas a cada evento
            foreach ($bonificacionesUsers as $bonificacionesUser) {
               if ($bonificacionesUser) {
                    $bonificacionesUser->delete();
               }
               
            }

            // Eliminar la bonificacion principal si existe
            $bonificacion = Bonificacion::findOrFail($id);
            if ($bonificacion) {
                $bonificacion->delete();
            }
    
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Bonificacion eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar bonificación: ' . $e->getMessage()], 500);
        }

    }

    /* obtener datos para formulario de modificar */

    public function editBonificaciones($id){
        $bonificaciones = Bonificacion::findOrFail($id);
        return response()->json($bonificaciones);
    }

    /* actualizar una bonificacion */
    
    public function actualizarBonificaciones($id, Request $request){
        try {
            DB::beginTransaction();

            $bonificacion = Bonificacion::findOrFail($id);
    
            $name = $request->input('nombre');
            $descripcion = $request->input('descripcion');
            $puntos = $request->input('puntos');

            $existingName = Bonificacion::where('name', $name)->where('id', '!=', $id)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }

            $existingDescripcion = Bonificacion::where('descripcion', $descripcion)->where('id', '!=', $id)->first();
            if ($existingDescripcion) {
                return response()->json(['error' => 'Descripcion ya está en uso '], 422);
            }

            $existingPuntos = Bonificacion::where('puntos', $puntos)->where('id', '!=', $id)->first();
            if ($existingPuntos) {
                return response()->json(['error' => 'Puntos ya está en uso '], 422);
            }

            $bonificacion->name = $name;
            $bonificacion->descripcion = $descripcion;
            $bonificacion->puntos = $puntos;
            $bonificacion->save();

            DB::commit();

            return response()->json(['message' => 'Bonificacion actualizada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar bonificacion: ' . $e->getMessage()], 500);
        }

    }

    /* insertar una nueva bonificación */

    public function storeBonificacion(Request $request){
        try {
            DB::beginTransaction();

            $name = $request->input('nombre');
            $descripcion = $request->input('descripcion');
            $puntos = $request->input('puntos');

            $existingName = Bonificacion::where('name', $name)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }

            $existingDescripcion = Bonificacion::where('descripcion', $descripcion)->first();
            if ($existingDescripcion) {
                return response()->json(['error' => 'Descripcion ya está en uso '], 422);
            }

            $existingPuntos = Bonificacion::where('puntos', $puntos)->first();
            if ($existingPuntos) {
                return response()->json(['error' => 'Puntos ya está en uso '], 422);
            }

            $bonificacion = new Bonificacion();
            $bonificacion->name = $name;
            $bonificacion->descripcion = $descripcion;
            $bonificacion->puntos = $puntos;
            $bonificacion->save();

            DB::commit();

            return response()->json(['message' => 'Bonificacion creada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al crear bonificacion: ' . $e->getMessage()], 500);
        }


    }

    /* CRUD CIUDADES */

    /* que se vean las ciudades */
    public function showCrudCiudades(Request $request){
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda'); 
            $ciudades = Ciudad::where('name', 'like', "%$data%")->get();
        } else {
            $ciudades = Ciudad::all();
        }
        return response()->json($ciudades);
    }

    /* eliminar ciudad */
    public function EliminarCiudades($id){
        try {
            DB::beginTransaction();

            // Obtener las discotecas asociadas a la ciudad
            $discotecas = discotecas::where('id_ciudad', $id)->get();
     
           
            // Eliminar las discotecas asociadas a la ciudad
            foreach ($discotecas as $discoteca) {
            /*     dd($discoteca); */
                $gestor = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $discoteca->id)
                ->where('users.id_rol', 3) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->first();
              /*   dd($gestor); */
       
              if ($gestor) {
                // Si $gestor no es null
                $gestorID = $gestor->id;
                /* dd($gestor); */
                            
                // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
                $channels = DB::table('ch_channels')->where('owner_id', $gestorID)->get();
                if ($channels->isNotEmpty()) {
                    $channelIds = $channels->pluck('id');
                    DB::table('ch_channel_user')->whereIn('channel_id', $channelIds)->delete();
                    
                    // Eliminar mensajes del usuario
                    DB::table('ch_messages')->where('to_channel_id', $channelIds)->delete();
                    DB::table('ch_favorites')->where('favorite_id', $channelIds)->delete();
                    
                    DB::table('ch_channels')->whereIn('id', $channelIds)->delete();
                }
            }
                $eventos = eventos::where('id_discoteca', $discoteca->id)->get();
                foreach($eventos as $evento){
                    $playlist = PlaylistCancion::where('id_evento', $evento->id);
                    if ($playlist) {
                        $playlist->delete();
                    }
                    $carritos = Carrito::where('id_evento', $evento->id)->get();
                    foreach ($carritos as $carrito) {
                        if ($carrito) {
                            $carrito->delete();
                        }

                    }

                    $valoraciones = Valoracion::where('id_evento', $evento->id)->get();
                    foreach ($valoraciones as $valoracion) {
                        if ($valoracion) {
                            $valoracion->delete();
                        }

                    }
                    if ($evento) {
                        $evento->delete();
                    }
                   

                }
                
                
                

                // Eliminar registros de users_discoteca asociados a la discoteca
                $userdiscoteca = UserDiscoteca::where('id_discoteca', $discoteca->id)->get();
                foreach($userdiscoteca as $userdiscotecas){
                    if ($userdiscotecas) {
                        $userdiscotecas->delete();
                    }

                }
                
                if ($discoteca) {
                    // Eliminar la discoteca
                    $discoteca->delete();
                }
               
            }


    
            // Eliminar la ciudad principal si existe
            $ciudades = Ciudad::findOrFail($id);
            if ($ciudades) {
                $ciudades->delete();
            }
    
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Ciudad eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar ciudad: ' . $e->getMessage()], 500);
        }

    }

    /* datos formulario modificar ciudad */

    public function editCiudades($id){
        $ciudades = Ciudad::findOrFail($id);
        return response()->json($ciudades);

    }

    public function actualizarCiudades($id, Request $request){
        try {
            DB::beginTransaction();

            $ciudad = Ciudad::findOrFail($id);
    
            $name = $request->input('nombre');
       

            $existingName = Ciudad::where('name', $name)->where('id', '!=', $id)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }

            

            $ciudad->name = $name;
       
            $ciudad->save();

            DB::commit();

            return response()->json(['message' => 'Bonificacion actualizada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar bonificacion: ' . $e->getMessage()], 500);
        }

    }

    public function storeCiudad(Request $request){
        try {
            DB::beginTransaction();

            $name = $request->input('nombre');
           

            $existingName = Ciudad::where('name', $name)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }


            $ciudad = new Ciudad();
            $ciudad->name = $name;
            $ciudad->save();

            DB::commit();

            return response()->json(['message' => 'Ciudad creada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al crear ciudad: ' . $e->getMessage()], 500);
        }


    }

    /* CRUD EVENTOS */

    /* Que se muestren los datos en el crud */
    public function showCrudEventos(Request $request){
        $query = DB::table('eventos')
            ->select('eventos.*', 'discotecas.name as nombre_discoteca')
            ->join('discotecas', 'eventos.id_discoteca', '=', 'discotecas.id');
        
        // Verificar si se proporcionó un filtro de búsqueda
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda');
            $query->where('eventos.name', 'like', "%$data%");
        }
        
        // Verificar si se proporcionó un filtro de discoteca
        if ($request->input('discoteca')) {
            $discotecaId = $request->input('discoteca');
            $query->where('discotecas.id', $discotecaId);
        }

        // Ordenar por el ID de la discoteca por defecto si no se especifica otro criterio de orden
        $query->orderBy('eventos.id');
        
        $discotecas = $query->get();
        
        return response()->json($discotecas);
    }

    public function EliminarEventos($id){
        try {
            DB::beginTransaction();
            // Obtener el evento antes de eliminarlo
            $evento = eventos::findOrFail($id);
    
            // Obtener la discoteca relacionada con el evento
            $discoteca_correo = Discoteca::findOrFail($evento->id_discoteca);
    
            // Formatear la fecha de inicio
            $evento->fecha_inicio = date('d/m/Y H:i', strtotime($evento->fecha_inicio));
    
            // Obtener todos los camareros de la discoteca
            $camareros = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $evento->id_discoteca)
                ->whereIn('users.id_rol', [3, 4]) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->get();

            $gestor = DB::table('users')
                ->join('users_discotecas', 'users.id', '=', 'users_discotecas.id_users')
                ->where('users_discotecas.id_discoteca', $evento->id_discoteca)
                ->where('users.id_rol', 3) // Suponiendo que el rol de camarero tiene id_rol = 4
                ->select('users.*')
                ->first();
                $gestorID = $gestor->id;
                /* dd($gestor); */
            
 
                // Verificar si hay canales asociados al usuario y eliminarlos condicionalmente
                $channels = DB::table('ch_channels')->where('owner_id', $gestorID)->where('name', $evento->name)->first();
                if ($channels) {
                 $channelIds = $channels->pluck('id');
                 DB::table('ch_channel_user')->where('channel_id', $channelIds)->delete();
                 
                    // Eliminar mensajes del usuario
                 DB::table('ch_messages')->where('to_channel_id', $channelIds)->delete();
                 DB::table('ch_favorites')->where('favorite_id', $channelIds)->delete();

                   DB::table('ch_channels')->where('id', $channelIds)->delete();
                }

                DB::table('registro_entradas')->where('evento_id', $id)->delete();
                
    
            // Enviar correo a todos los camareros
            foreach ($camareros as $camarero) {
                Mail::to($camarero->email)->send(new EventoEliminadoMail($camarero, $discoteca_correo, $evento));
            }
    

            // Obtener las discotecas asociadas a la ciudad
            $playlist = PlaylistCancion::where('id_evento', $id)->first();
            if ($playlist) {
                $playlist->delete();
            }
           
            eventos::findOrFail($id)->delete();
    
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Ciudad eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar ciudad: ' . $e->getMessage()], 500);
        }

    }

    /* CRUD CANCIONES */

    /* mostrar crud con filtros */
    public function showCrudCanciones(Request $request){
        $query = DB::table('canciones')
            ->join('artistas_canciones', 'canciones.id', '=', 'artistas_canciones.id_cancion')
            ->join('artistas', 'artistas_canciones.id_artista', '=', 'artistas.id')
            ->select('canciones.*', 'artistas.name as nombre_artista');
           
        // Verificar si se proporcionó un filtro de búsqueda
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda');
            $query->where('canciones.name', 'like', "%$data%");
        }
    
        // Verificar si se proporcionó un filtro de ciudad
        if ($request->input('artista')) {
            $artistaID = $request->input('artista');
            $query->where('artistas_canciones.id_artista', $artistaID);
        }
    
        // Ordenar por el ID de la discoteca
        $query->orderBy('canciones.id');
    
        $artistas = $query->get(); 
    
        return response()->json($artistas);

    }

    /* mostrar en el desplegable artistas */
    public function obtenerArtistas(){
        $artistas = Artista::all();
        return response()->json($artistas);

    }


    /* eliminar canciones */

    public function EliminarCanciones($id){
        try {
            DB::beginTransaction();
            $ArtistasCanciones= ArtistaCancion::where("id_cancion", $id)->get();
            foreach($ArtistasCanciones as $ArtistasCancion){
                $ArtistasCancion->delete();
            }
            PlaylistCancion::where("id_canciones", $id)->delete();
            Cancion::findOrFail($id)->delete();
            Producto::findOrFail($id)->delete();
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Canción eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar canción: ' . $e->getMessage()], 500);
        }
    }

    /* mostrar form editar */
    public function editCanciones($id){

        $canciones = Cancion::findOrFail($id);
        $canciones_artistas = ArtistaCancion::where("id_cancion", $id)->first();
        $ArtistaID = $canciones_artistas->id_artista;
        $artista = Artista::where("id", $ArtistaID)->first();
        return response()->json([
            'canciones' => $canciones,
            'artista' => $artista,
        ]);

    }

    /* actualizar cancion */
    public function actualizarCanciones($id, Request $request){
        
        try {

            DB::beginTransaction();
            $cancion = Cancion::findOrFail($id);
            $nombre = $request->input("nombreCancion");
            $artista = $request->input("nombreArtista");

            $artitaQuery = Artista::where("name", $artista)->first();
            $artistaID = $artitaQuery->id;
        
            // Verificar si ya existe una canción con el mismo nombre y el mismo ID de artista
            $existeCancion = DB::table('canciones')
                ->join('artistas_canciones', 'canciones.id', '=', 'artistas_canciones.id_cancion')
                ->where('canciones.name', $nombre)
                ->where('artistas_canciones.id_artista', $artistaID)
                ->where('canciones.id', '!=', $id) // Excluir la canción actual de la búsqueda
                ->exists();
        
            if ($existeCancion) {
                return response()->json(['error' => 'Ya existe una canción con este nombre asociada al mismo artista.'], 400);
            }
    
            $cancion->name = $nombre;
            $cancion->save();
    
            $producto = Producto::findOrFail($id);
            $producto->name = $nombre;
            $producto->save();
    
            $ArtistasCancion = ArtistaCancion::where("id_cancion", $id)->first();
            $ArtistasCancion->id_artista = $artistaID;
            $ArtistasCancion->save();
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Canción modificada correctamente en la base de datos']);
        } catch (\Exception $e) {
            // Revertir la transacción si hay un error
            DB::rollback();
    
            // Devolver una respuesta JSON con un indicador de éxito falso y un mensaje de error
            return response()->json(['success' => false, 'message' => 'Hubo un problema al modificar la canción en la base de datos'], 500);
        }
    }

    public function storeCancion(Request $request) {
        try {
            DB::beginTransaction();
    
            $nombre = $request->input("nombreCancion");
            $artista = $request->input("nombreArtista");
            $ArtistaQuery = Artista::where("name", $artista)->first();
    
            if (!$ArtistaQuery) {
                return response()->json(['success' => false, 'message' => 'Artista no encontrado.'], 404);
            }
    
            $artistaID = $ArtistaQuery->id;
    
            // Verificar si ya existe una canción con el mismo nombre y el mismo ID de artista
            $existeCancion = DB::table('canciones')
                ->join('artistas_canciones', 'canciones.id', '=', 'artistas_canciones.id_cancion')
                ->where('canciones.name', $nombre)
                ->where('artistas_canciones.id_artista', $artistaID)
                ->exists();
    
            if ($existeCancion) {
                return response()->json(['success' => false, 'message' => 'Ya existe una canción con este nombre asociada al mismo artista.'], 400);
            }
    
            $producto = new Producto();
            $producto->name = $nombre;
            $producto->save();
            $productoID = $producto->id;
    
            $cancion = new Cancion;
            $cancion->id = $productoID;
            $cancion->name = $nombre;
            $cancion->precio = "3.00";
            $cancion->save();
    
            $ArtistasCancion = new ArtistaCancion();
            $ArtistasCancion->id_cancion = $producto->id;
            $ArtistasCancion->id_artista = $artistaID;
            $ArtistasCancion->save();
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Canción insertada correctamente en la base de datos']);
        } catch (\Exception $e) {
            // Revertir la transacción si hay un error
            DB::rollback();
    
            // Devolver una respuesta JSON con un indicador de éxito falso y un mensaje de error
            return response()->json(['success' => false, 'message' => 'Hubo un problema al intentar insertar la canción en la base de datos'], 500);
        }
    }
    

    /* CRUD ARTISTA */

    /* mostrar artistas con filtros */

    public function showCrudArtistas(Request $request){

        if ($request->input('busqueda')) {
            $data = $request->input('busqueda'); 
            $artistas = Artista::where('name', 'like', "%$data%")->get();
        } else {
            $artistas = Artista::all();
        }
        return response()->json($artistas);

    }

    /* eliminar el artista */

    public function EliminarArtistas($id){
        try {
            DB::beginTransaction();
            $ArtistasCanciones= ArtistaCancion::where("id_artista", $id)->get();
            foreach($ArtistasCanciones as $ArtistasCancion){
                $idCancion = $ArtistasCancion->id_cancion;
                $ArtistasCancion->delete();
                PlaylistCancion::where("id_canciones", $idCancion)->delete();
                Cancion::findOrFail($idCancion)->delete();
                Producto::findOrFail($idCancion)->delete();
                
            }
            Artista::findOrFail($id)->delete();
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Artista eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => 'Error al eliminar artista: ' . $e->getMessage()], 500);
        }

    }

    /* datos editar artistas */

    public function editArtistas($id){
        $artistas = Artista::findOrFail($id);
        return response()->json($artistas);

    }

    /* actualizar artistas */
    public function actualizarArtistas($id, Request $request){
        try {
            DB::beginTransaction();

            $artista = Artista::findOrFail($id);
    
            $name = $request->input('nombre');
       

            $existingName = Artista::where('name', $name)->where('id', '!=', $id)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }

            

            $artista->name = $name;
       
            $artista->save();

            DB::commit();

            return response()->json(['message' => 'Artista actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar artista: ' . $e->getMessage()], 500);
        }


    }

    /* insertar artista */

    public function storeArtista(Request $request){
        try {
            DB::beginTransaction();

            $name = $request->input('nombre');
           

            $existingName = Artista::where('name', $name)->first();
            if ($existingName) {
                return response()->json(['error' => 'El nombre ya está en uso '], 422);
            }


            $ciudad = new Artista();
            $ciudad->name = $name;
            $ciudad->save();

            DB::commit();

            return response()->json(['message' => 'Artista creado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al crear artista: ' . $e->getMessage()], 500);
        }

    }


    /* mostrar filtro mas registros */
    public function showCrudEntrada(Request $request){
        $query = DB::table('registro_entradas')
            ->select('registro_entradas.*', 'discotecas.name as nombre_discoteca', 'users.email as email_usuario', 'eventos.name as evento_name')
            ->join('eventos', 'eventos.id', '=', 'registro_entradas.evento_id')
            ->join('discotecas', 'discotecas.id', '=', 'eventos.id_discoteca')
            ->join('users', 'users.id', '=', 'registro_entradas.id_user');
        
        // Verificar si se proporcionó un filtro de búsqueda
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda');
            $query->where('registro_entradas.id', 'like', "%$data%")->orWhere('users.email', 'like', "%$data%");
        }
        
        // Verificar si se proporcionó un filtro de discoteca
        if ($request->input('discoteca')) {
            $discotecaId = $request->input('discoteca');
            $query->where('discotecas.id', $discotecaId);
        }

        // Ordenar por el ID de la discoteca por defecto si no se especifica otro criterio de orden
        $query->orderBy('registro_entradas.fecha');
        
        $discotecas = $query->get();
      /*   dd($discotecas); */
        
        return response()->json($discotecas);
    }


    
    
}
