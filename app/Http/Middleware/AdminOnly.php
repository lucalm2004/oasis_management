<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario es un administrador
        if (Auth::user()->id_rol == 1 && Auth::user()->habilitado == 1) {
            return $next($request);
        }else{
            return redirect('/');
            

        }
    
        return redirect('/login');
    }
}