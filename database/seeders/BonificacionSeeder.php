<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bonificacion;

class BonificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear bonificaciones de ejemplo
        Bonificacion::create([
            'name' => 'Consumición extra',
            'descripcion' => 'Si canjeas esta bonificación tendrás una consumición extra',
            'puntos' => 100,
        ]);

        Bonificacion::create([
            'name' => 'Subes con el DJ',
            'descripcion' => 'Si canjeas esta bonificación podrás subir a disfrutar con el DJ',
            'puntos' => 200,
        ]);

        // Puedes agregar más bonificaciones según sea necesario
    }
}
