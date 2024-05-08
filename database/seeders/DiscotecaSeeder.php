<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discoteca;

class DiscotecaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear discotecas de ejemplo
        Discoteca::create([
            'name' => 'Club Nocturno Luna',
            'direccion' => 'Calle Luna, 123',
            'image' => 'imagen_discoteca_luna.jpg', // Nombre de la imagen (si existe)
            'lat' => 40.7128, // Latitud de la ubicación
            'long' => -74.0060, // Longitud de la ubicación
            'capacidad' => 500, // Capacidad máxima de la discoteca
            'id_ciudad' => 1, // ID de la ciudad a la que pertenece esta discoteca
        ]);

        Discoteca::create([
            'name' => 'Fiesta en el Cielo',
            'direccion' => 'Avenida Estrella, 456',
            'image' => 'imagen_discoteca_fiesta.jpg', // Nombre de la imagen (si existe)
            'lat' => 34.0522, // Latitud de la ubicación
            'long' => -118.2437, // Longitud de la ubicación
            'capacidad' => 700, // Capacidad máxima de la discoteca
            'id_ciudad' => 2, // ID de la ciudad a la que pertenece esta discoteca
        ]);

    }
}
