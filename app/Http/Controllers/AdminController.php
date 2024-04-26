<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
