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
            'name' => 'Fiesta en el Cielo',
            'direccion' => 'Avenida Estrella, 456',
            'image' => '1716040771_images.jpg', // Nombre de la imagen (si existe)
            'lat' => 36.721261, // Latitud de la ubicación
            'long' => -4.4212655, // Longitud de la ubicación
            'capacidad' => 800, // Capacidad máxima de la discoteca
            'id_ciudad' => 1, // ID de la ciudad a la que pertenece esta discoteca
        ]);

        Discoteca::create([
            'name' => 'BeDisco',
            'direccion' => 'Carrer de Miquel Torelló i Pagès',
            'image' => '1715002826_bedisco.webp', // Nombre de la imagen (si existe)
            'lat' => 41.4000723, // Latitud de la ubicación
            'long' => 2.0271679, // Longitud de la ubicación
            'capacidad' => 700, // Capacidad máxima de la discoteca
            'id_ciudad' => 2, // ID de la ciudad a la que pertenece esta discoteca
        ]);
          // Crear discotecas de ejemplo
          Discoteca::create([
            'name' => 'La Costa',
            'direccion' => 'Salvador Allende',
            'image' => '1715002980_images (1).jpg',
            'lat' => 41.1188827,
            'long' => 1.2444909,
            'capacidad' => 900,
            'id_ciudad' => 2,
       
        ]);

        Discoteca::create([
            'name' => 'Wolf',
            'direccion' => 'Carrer dels Almogàvers',
            'image' => '1715003140_wolf-barcelona.png',
            'lat' => 41.3966198,
            'long' => 2.1894029,
            'capacidad' => 800,
            'id_ciudad' => 3,
        ]);
        Discoteca::create([
            'name' => 'Pacha',
            'direccion' => 'Avinguda del Professor López Piñero',
            'image' => '1715004121_Pacha_logo.png',
            'lat' => 39.4559624,
            'long' => -0.3552235,
            'capacidad' => 900,
            'id_ciudad' => 4,
        ]);

    }
}
