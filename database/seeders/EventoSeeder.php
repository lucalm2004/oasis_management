<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use Illuminate\Support\Facades\Storage;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear eventos de ejemplo
        Evento::create([
            'name' => 'Fiesta de inauguración',
            'descripcion' => '¡Ven y únete a nosotros en nuestra gran fiesta de inauguración!',
            'flyer' => 'imagen_fiesta_inauguracion.jpg',
            'fecha_inicio' => '2024-04-30 20:00:00',
            'fecha_final' => '2024-05-01 04:00:00',
            'dj' => 'DJ Invitado',
            'name_playlist' => 'Playlist de la Fiesta',
            'id_discoteca' => 1, // Suponiendo que esta es la ID de una discoteca existente
        ]);

        Evento::create([
            'name' => 'Noche de salsa',
            'descripcion' => '¡Una noche llena de ritmo y sabor latino!',
            'flyer' => 'imagen_noche_salsa.jpg',
            'fecha_inicio' => '2024-05-05 22:00:00',
            'fecha_final' => '2024-05-06 04:00:00',
            'dj' => 'DJ Salsa',
            'name_playlist' => 'Salsa Playlist',
            'id_discoteca' => 2, // Suponiendo que esta es la ID de otra discoteca existente
        ]);

        // Puedes agregar más eventos según sea necesario
    }

    /**
     * Almacena el flyer en el almacenamiento y devuelve el nombre del archivo.
     *
     * @param string $fileName
     * @return string
     */
    private function storeFlyer($fileName)
    {
        $fileContent = file_get_contents(public_path('images/' . $fileName)); // Suponiendo que las imágenes están en la carpeta 'images' en la carpeta 'public'
        $filePath = 'flyers/' . $fileName;
        Storage::disk('public')->put($filePath, $fileContent);
        return $filePath;
    }
}
