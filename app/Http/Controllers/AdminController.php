<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
