<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discoteca; // Asegúrate de importar el modelo Discoteca si es necesario

class ContactoController extends Controller
{
    /**
     * Muestra la página de contacto.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener las discotecas u otro tipo de información necesaria para la vista
        $discotecas = Discoteca::all(); // Ejemplo: Obtener todas las discotecas

        // Pasar las discotecas u cualquier otra información a la vista
        return view('contacto', ['discotecas' => $discotecas]);
    }
}
