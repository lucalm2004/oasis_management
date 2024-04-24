<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artista;

class ArtistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear artistas de ejemplo
        Artista::create([
            'name' => 'Anuel AA',
        ]);

        Artista::create([
            'name' => 'Dei V',
        ]);

        Artista::create([
            'name' => 'JC Reyes',
        ]);

        Artista::create([
            'name' => 'Myke Towers',
        ]);

        Artista::create([
            'name' => 'Mora',
        ]);

        Artista::create([
            'name' => 'Anonymous',
        ]);

        Artista::create([
            'name' => 'Bryant Myers',
        ]);

        Artista::create([
            'name' => 'Lary Over',
        ]);

        Artista::create([
            'name' => 'Almighty',
        ]);
    }
}
