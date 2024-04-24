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
            'name' => 'Canción a tu elección',
            'tipo' => 'Cancion',
        ]);

        // Puedes agregar más productos según sea necesario
    }
}
