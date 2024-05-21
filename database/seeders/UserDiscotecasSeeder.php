<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDiscoteca;

class UserDiscotecasSeeder extends Seeder
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
            'id_discoteca' => 1,
            'id_users' => 2,
        ]);

        // Otro ejemplo, usuario con ID 2 asistió a la discoteca con ID 2
        UserDiscoteca::create([
            'id_discoteca' => 1,
            'id_users' => 6,
        ]);

        // Puedes agregar más relaciones según sea necesario
    }
}
