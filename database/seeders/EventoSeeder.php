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
            'name' => 'Noche de salsa',
            'descripcion' => '¡Una noche llena de ritmo y sabor latino!',
            'flyer' => 'magen_noche_salsa.jpg',
            'fecha_inicio' => '2024-05-31 23:30:00',
            'fecha_final' => '2024-06-01 06:00:00',
            'dj' => 'DJ Salsas',
            'name_playlist' => 'Salsa Playlist',
            'id_discoteca' => 1, // Suponiendo que esta es la ID de una discoteca existente
            'capacidad' => 790, // Suponiendo que esta es la ID de una discoteca existente
            'capacidadVip' => 10, // Suponiendo que esta es la ID de una discoteca existente
        ]);

        Evento::create([
            'name' => 'Semáforo',
            'descripcion' => 'Bienvenidos a la Fiesta de Semáforo. Depende de tu color será lo que buscarás esa noche. Rojo no te interesa nada, intermitenmte te lo piensas y verde locurote.',
            'flyer' => '1715005345_semaforo.jpg',
            'fecha_inicio' => '2024-08-10 23:30:00',
            'fecha_final' => '2024-08-11 06:00:00',
            'dj' => 'DjMariio',
            'name_playlist' => 'Desatada',
            'id_discoteca' => 1, // Suponiendo que esta es la ID de otra discoteca existente
            'capacidad' => 790, // Suponiendo que esta es la ID de una discoteca existente
            'capacidadVip' => 10, // Suponiendo que esta es la ID de una discoteca existente
        ]);
        
        Evento::create([
            'name' => 'PerreoLand',
            'descripcion' => 'Estais preparados para el perreo? Pues no te lo pienses y ven a PerreoLand donde el DR WALTER ALEJANDRO, doctorado en el perreo',
            'flyer' => '1716042851_perrreooo.jpg',
            'fecha_inicio' => '2024-09-11 23:30:00',
            'fecha_final' => '2024-09-12 06:00:00',
            'dj' => 'WalterAlejandro',
            'name_playlist' => 'PerreoCity',
            'id_discoteca' => 1, // Suponiendo que esta es la ID de otra discoteca existente
            'capacidad' => 790, // Suponiendo que esta es la ID de una discoteca existente
            'capacidadVip' => 10, // Suponiendo que esta es la ID de una discoteca existente
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
