<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserDiscoteca;
use Illuminate\Support\Facades\Auth;

class CamareroOnly
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $UserDiscoteca = UserDiscoteca::where("id_users", Auth::user()->id)->first();
        if (Auth::user()->id_rol == 4 && Auth::user()->habilitado == 1  && $UserDiscoteca) {
            return $next($request);
        }else{
            return redirect('/');
            

        }
    
        return redirect('/login');
    }
}
