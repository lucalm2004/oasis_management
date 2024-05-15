<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discoteca;
use App\Models\User;
use App\Models\Valoraciones;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Retrieve all discotecas from the database
        $discotecas = Discoteca::all();

        // Count the number of discotecas
        $numClubes = $discotecas->count();

        // Count the number of users
        $numUsuarios = User::count();

        // Calculate the average star rating for discotecas
        $mediaEstrellas = Valoraciones::avg('rating');

        // Round the average rating to one decimal place
        $mediaEstrellas = round($mediaEstrellas, 1);

        return view('welcome', compact('discotecas', 'numClubes', 'numUsuarios', 'mediaEstrellas'));
    }
}
