<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArtistaCancion;

class ArtistasCancionesSeeder extends Seeder
{
    public function run()
    {
        ArtistaCancion::insert([
            ['id_artista' => 1, 'id_cancion' => 4, 'created_at' => null, 'updated_at' => null],
            ['id_artista' => 5, 'id_cancion' => 6, 'created_at' => null, 'updated_at' => null],
            ['id_artista' => 4, 'id_cancion' => 5, 'created_at' => null, 'updated_at' => null],
            ['id_artista' => 13, 'id_cancion' => 7, 'created_at' => '2024-05-15 16:16:17', 'updated_at' => '2024-05-15 16:16:17'],
            ['id_artista' => 10, 'id_cancion' => 8, 'created_at' => '2024-05-16 11:57:56', 'updated_at' => '2024-05-16 12:31:33']
        ]);
    }
}
