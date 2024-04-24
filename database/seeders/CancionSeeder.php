<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancion;

class CancionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Cancion::create([
            'name' => 'Exclava Remix',
            'duracion' => '00:04:44',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'name' => 'Calentón',
            'duracion' => '00:03:24',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'name' => 'La Playa',
            'duracion' => '00:03:45',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'name' => 'Fardos',
            'duracion' => '00:03:04',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'name' => 'Perreo Lento',
            'duracion' => '00:02:36',
            'precio' => 3.00,
        ]);
    }
}
