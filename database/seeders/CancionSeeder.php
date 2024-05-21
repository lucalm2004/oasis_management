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
            'id' => 4,
            'name' => 'Amanece',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'id' => 5,
            'name' => 'La Playa',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'id' => 6,
            'name' => 'Calentón',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'id' => 7,
            'name' => 'Las Bratz',
            'precio' => 3.00,
        ]);

        Cancion::create([
            'id' => 8,
            'name' => 'Soy Peor',
            'precio' => 3.00,
        ]);
    }
}
