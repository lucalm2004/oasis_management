<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BonificacionUser;

class UsersBonificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear relaciones de usuarios y bonificaciones manualmente
        BonificacionUser::create([
            'id_users' => 2, // ID de usuario
            'id_bonificacion' => 1, // ID de bonificación
        ]);

        BonificacionUser::create([
            'id_users' => 3, // ID de usuario
            'id_bonificacion' => 2, // ID de bonificación
        ]);

        // Puedes agregar más relaciones según sea necesario
    }
}
