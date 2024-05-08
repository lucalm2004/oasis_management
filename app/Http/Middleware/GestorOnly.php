<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class GestorOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {/* 
        dd(Auth::user()->verificado); */
        if (Auth::user()->id_rol == 3 && Auth::user()->verificado == 1 && Auth::user()->habilitado == 1) {
                return $next($request);
        }else{
            return redirect('/');
            

        }
        return redirect('/login');
        
            
    }
   
}
