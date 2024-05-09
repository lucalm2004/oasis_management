<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CamareroOnly
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->id_rol == 4 && Auth::user()->habilitado == 1) {
            return $next($request);
        }else{
            return redirect('/');
            

        }
    
        return redirect('/login');
    }
}
