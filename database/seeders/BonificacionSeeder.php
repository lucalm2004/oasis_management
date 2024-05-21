<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bonificacion;

class BonificacionSeeder extends Seeder
{
    public function run()
    {
        Bonificacion::create([
            'name' => 'Consumición extra',
            'descripcion' => 'Si canjeas esta bonificación tendrás una consumición extra',
            'puntos' => 800,
        ]);

        Bonificacion::create([
            'name' => 'Subir con el DJ',
            'descripcion' => 'En esta bonificación podrás tener mesa con el Dj',
            'puntos' => 500,
        ]);

        Bonificacion::create([
            'name' => 'Mesa VIP',
            'descripcion' => 'Consigue una mesa VIP con botella incluida',
            'puntos' => 3000,
        ]);

        Bonificacion::create([
            'name' => 'Descuento GUESS',
            'descripcion' => 'Puedes obtener un descuento de un 10% en GUESS',
            'puntos' => 1000,
        ]);
    }
}
