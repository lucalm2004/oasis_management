<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArtistaCancion;

class ArtistasCancionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Relacionar canciones con artistas
        ArtistaCancion::create([
            'id_artista' => 1, // ID del artista
            'id_cancion' => 4, // ID de la canción
        ]);

        ArtistaCancion::create([
            'id_artista' => 6, // ID del artista
            'id_cancion' => 4, // ID de la canción
        ]);

        ArtistaCancion::create([
            'id_artista' => 7, // ID del otro artista
            'id_cancion' => 4, // ID de la misma canción o otra
        ]);

        ArtistaCancion::create([
            'id_artista' => 9, // ID del otro artista
            'id_cancion' => 4, // ID de la misma canción o otra
        ]);

        ArtistaCancion::create([
            'id_artista' => 5, // ID del otro artista
            'id_cancion' => 5, // ID de la misma canción o otra
        ]);

        ArtistaCancion::create([
            'id_artista' => 4, // ID del otro artista
            'id_cancion' => 6, // ID de la misma canción o otra
        ]);

        ArtistaCancion::create([
            'id_artista' => 3, // ID del otro artista
            'id_cancion' => 7, // ID de la misma canción o otra
        ]);

        ArtistaCancion::create([
            'id_artista' => 2, // ID del otro artista
            'id_cancion' => 8, // ID de la misma canción o otra
        ]);

    }
}
