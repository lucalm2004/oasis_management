<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showCrudUsers(Request $request)
    {
         

        if ($request->input('busqueda')) {
            $data = $request->input('busqueda'); 
            $users = User::with('rol')
                ->where('name', 'like', "%$data%")
                ->orWhere('email', 'like', "%$data%")
                ->get();
        } else {
            $users = User::with('rol')->get();
        }

        return response()->json($users);
    }
}
