<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discoteca; // Importa el modelo de Discoteca

class WelcomeController extends Controller
{
    public function index()
    {
        $discotecas = Discoteca::all(); // Obtener todas las discotecas desde la base de datos

        return view('welcome', compact('discotecas'));
    }
}
