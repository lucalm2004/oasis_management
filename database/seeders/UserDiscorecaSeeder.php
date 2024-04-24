<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDiscoteca;

class UserDiscotecaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear relaciones de ejemplo entre usuarios y discotecas
        // Por ejemplo, usuario con ID 1 asistió a la discoteca con ID 1
        UserDiscoteca::create([
            'user_id' => 4,
            'discoteca_id' => 1,
        ]);

        // Otro ejemplo, usuario con ID 2 asistió a la discoteca con ID 2
        UserDiscoteca::create([
            'user_id' => 5,
            'discoteca_id' => 2,
        ]);

        // Puedes agregar más relaciones según sea necesario
    }
}
