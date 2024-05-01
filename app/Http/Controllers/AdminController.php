<?php

namespace App\Http\Controllers;

use App\Models\Bonificacion;
use App\Models\Rol;
use App\Models\User;
use App\Models\UserDiscoteca;
use App\Models\Discoteca;
use App\Models\BonificacionUser;
use App\Models\Carrito;
use App\Models\Valoracion;
use App\Models\Ciudad;
use App\Models\Evento;
use App\Models\PlaylistCancion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* CRUD DISCOTECAS */

    /* ver usuarios */
   
    public function showCrudUsers(Request $request)
    {
          $query = User::query();
  
          // Verificar si se proporcionó un filtro de búsqueda
          if ($request->input('busqueda')) {
              $data = $request->input('busqueda');
              $query->where('name', 'like', "%$data%")
                  ->orWhere('email', 'like', "%$data%");
          }
  
          // Verificar si se proporcionó un filtro de rol
          if ($request->input('rol')) {
              $rolId = $request->input('rol');
              $query->where('id_rol', $rolId);
          }
           // Excluir los usuarios con verificado = 0
            $query->where('verificado', '=', 1);
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
        $discotecas = Discoteca::all();
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

        
        
        
        if ($discotecaID !== null) {
            if ($request->input('rol') == 3) {
                // Buscar un gestor diferente al usuario que se está actualizando en la misma discoteca
                $existingGestor = UserDiscoteca::where('id_discoteca', $discotecaID)
                                                ->where('id_users', '!=', $id) // Excluir el usuario actual
                                                ->whereHas('user', function ($query) {
                                                    $query->where('id_rol', 3); // Filtrar por usuarios con rol de gestor
                                                })
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
          
            }
        }else {
            DB::table('users_discotecas')->where('id_users', $id)->delete();
          
            
        }

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
            foreach($BonificacionUsers as $BonificacionUser)
            if($BonificacionUser){
                $BonificacionUser->delete();
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
                $gestorExistente = UserDiscoteca::where('id_discoteca', $discoteca)
                ->whereHas('user', function ($query) {
                    $query->where('id_rol', 3); // 3 representa el ID del rol de gestor
                })
                ->exists();

                if ($gestorExistente) {
                    return response()->json(['error' => 'La discoteca ya tiene un gestor asignado'], 422);
                }

              
                $userdiscoteca =  new UserDiscoteca();
                $userdiscoteca->id_users = $user ->id;
                $userdiscoteca->id_discoteca = $discoteca;
                $userdiscoteca->save();
            }
           

           /*  $discotecaNuevaName = $request->input('nombreDiscoteca');
            if ($discotecaNuevaName !== null) {
                $discotecaNueva = new Discoteca();
                $discotecaNueva->name = $discotecaNuevaName;
                $discotecaNueva->direccion = $request->input('direccion');
      
            
                $imagen = $request->file('imagen');
                $originalName = $imagen->getClientOriginalName();
                $imageName = time() . '_' . $originalName;
                $imagePath = 'img/discotecas';
                // Almacenar la imagen en la carpeta 'public/img'
                $imagen->move(public_path($imagePath), $imageName);

                // Guardar el nombre de la imagen en la base de datos
                $discotecaNueva->image = $imageName;
                
                $discotecaNueva->lat = $request->input('latitud');
                $discotecaNueva->long = $request->input('longitud');
                $discotecaNueva->capacidad = $request->input('capacidad');
                $discotecaNueva->id_ciudad = $request->input('ciudad');
                $discotecaNueva->save();

                // Obtener el ID de la discoteca recién creada
                $newDiscotecaId = $discotecaNueva->id;

                // Asignar al usuario gestor a la discoteca recién creada
                $discotecaGestor = new UserDiscoteca();
                $discotecaGestor->id_users = $user->id; // Usar el ID del usuario recién creado
                $discotecaGestor->id_discoteca = $newDiscotecaId; // Usar el ID de la discoteca recién creada
                $discotecaGestor->save();

                

            } */


    
            DB::commit();
    
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
        ->get();
        return response()->json($solicitudes);

    }

    /* aceptar solicitudes */

    public function AceptarSolicitudes($id){
       
        try {
            DB::beginTransaction();
    
  
            // Eliminar el usuario principal si existe
            $user = User::findOrFail($id);
            $user-> verificado = 1;
            $user->save();
            
            // Confirmar la transacción
            DB::commit();
    
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
    
            // Eliminar registros relacionados de la tabla UserDiscoteca si existen
            $UserDiscotecas = UserDiscoteca::where('id_users', $id)->first();
            $UserDiscotecas->delete();

            Discoteca::where('id', $UserDiscotecas->id_discoteca)->delete();
             

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
        $query = Discoteca::query();

        // Verificar si se proporcionó un filtro de búsqueda
        if ($request->input('busqueda')) {
            $data = $request->input('busqueda');
            $query->where('name', 'like', "%$data%");
        }

        // Verificar si se proporcionó un filtro de rol
        if ($request->input('ciudad')) {
            $ciudadId = $request->input('ciudad');
            $query->where('id_ciudad', $ciudadId);
        }

      
        $discotecas = $query->with('ciudad')->get();

        return response()->json($discotecas);

    }

    /* obtener lista ciudades para desplegables */
    public function showCiudades(){
        $cuidades = Ciudad::all();
        return response()->json($cuidades);

    }

    public function EliminarDiscotecas($id){
        try {
            DB::beginTransaction();
    
            // Obtener todos los eventos asociados a la discoteca
            $eventos = Evento::where('id_discoteca', $id)->get();
    
            // Eliminar las playlists, carritos y valoraciones asociadas a cada evento
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
            $discoteca = Discoteca::findOrFail($id);
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
            $discotecas = Discoteca::where('id_ciudad', $id)->get();

            // Eliminar las discotecas asociadas a la ciudad
            foreach ($discotecas as $discoteca) {
                $eventos = Evento::where('id_discoteca', $discoteca->id)->get();
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

    
    
}
