<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoEntrada;

class TipoEntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear tipos de entrada de ejemplo
        TipoEntrada::create([
            'id' => 1,
            'descripcion' => 'Entrada de una consumición',
            'precio' => 10.00,
        ]);

        TipoEntrada::create([
            'id' => 2,
            'descripcion' => 'Entrada de dos consumiciones',
            'precio' => 15.00,
        ]);

        TipoEntrada::create([
            'id' => 3,
            'descripcion' => 'Entrada VIP',
            'precio' => 125.00,
        ]);

    }
}
