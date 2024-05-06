<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ClientOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario es un administrador
        if (Auth::user()->id_rol == 2 && Auth::user()->habilitado == 1) {
            return $next($request);
        }else{
            return redirect('/');
            

        }
    
        return redirect('/login');
    }
}