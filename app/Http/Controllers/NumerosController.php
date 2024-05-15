<?php

namespace App\Http\Controllers;

use App\Models\Discoteca;
use App\Models\User;
use Illuminate\Http\Request;

class NumerosController extends Controller
{
    public function obtenerNumeros()
    {
        $numClubes = Discoteca::count();
        $numUsuarios = User::count();
        $mediaEstrellas = Discoteca::avg('estrellas');

        return view('welcome', compact('numClubes', 'numUsuarios', 'mediaEstrellas'));
    }
}
