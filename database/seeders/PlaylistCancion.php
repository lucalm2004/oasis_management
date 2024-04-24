<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlaylistCancion;

class PlaylistsCancionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Relacionar canciones con eventos en las playlists
        PlaylistCancion::create([
            'id_evento' => 1, // ID del evento
            'id_canciones' => 1, // ID de la canción
        ]);

        PlaylistCancion::create([
            'id_evento' => 1, // ID del evento
            'id_canciones' => 2, // ID de otra canción
        ]);

        PlaylistCancion::create([
            'id_evento' => 2, // ID de otro evento
            'id_canciones' => 2, // ID de la misma canción o otra
        ]);

    }
}
