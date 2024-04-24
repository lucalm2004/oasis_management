<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear registros de ejemplo
        Rol::create(['name' => 'Admin']);
        Rol::create(['name' => 'Cliente']);
        Rol::create(['name' => 'Gestor']);
        Rol::create(['name' => 'Camarero']);
    }
}

