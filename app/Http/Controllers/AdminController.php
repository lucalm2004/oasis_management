<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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

        // Obtener los usuarios con la relación de rol cargada
        $users = $query->with('rol')->get();

        return response()->json($users);
    }

    public function showRoles()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }
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

    public function editUsers($id){
        $users = User::findOrFail($id);
        return response()->json($users);
    }
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

        // Validar que el DNI no esté duplicado
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

        // Confirmar la transacción
        DB::commit();

    
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar usuario: ' . $e->getMessage()], 500);
        }
    }
}
