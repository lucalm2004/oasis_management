<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear productos de ejemplo
        Producto::create([
            'name' => 'Entrada de 10 euros',
            'tipo' => 'Entrada',
        ]);

        Producto::create([
            'name' => 'Entrada de 15 euros',
            'tipo' => 'Entrada',
        ]);

        Producto::create([
            'name' => 'Entrada VIP',
            'tipo' => 'Entrada',
        ]);

        Producto::create([
            'name' => 'Amanece',
            'tipo' => 'Canción',

        ]);

        Producto::create([
            'name' => 'Calentón',
            'tipo' => 'Canción',
        ]);

        Producto::create([
            'name' => 'La Playa',
            'tipo' => 'Canción',
        ]);

        Producto::create([
            'name' => 'Las Bratz',
            'tipo' => 'Canción',
        ]);

        Producto::create([
            'name' => 'Soy Peor',
            'tipo' => 'Canción',
        ]);

    }
}
