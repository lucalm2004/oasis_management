<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener el ID del rol por defecto (por ejemplo, el rol de usuario)
        $userRole = Rol::where('name', 'Cliente')->firstOrFail();
        $AdminRole = Rol::where('name', 'Admin')->firstOrFail();
        $gestorRole = Rol::where('name', 'Gestor')->firstOrFail();
        $camareroRole = Rol::where('name', 'Camarero')->firstOrFail();

        User::create([
            'name' => 'Admin',
            'apellidos' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'habilitado' => true,
            'id_rol' => $AdminRole->id,
        ]);

        User::create([
            'name' => 'David',
            'apellidos' => 'Hernández',
            'email' => 'dhernandez@gmail.com',
            'password' => Hash::make('password'),
            'habilitado' => true,
            'id_rol' => $userRole->id,
        ]);

        User::create([
            'name' => 'Sergi',
            'apellidos' => 'Marín',
            'email' => 'smarin@gmail.com',
            'password' => Hash::make('password'),
            'habilitado' => true,
            'id_rol' => $userRole->id,
        ]);

        User::create([
            'name' => 'Luca',
            'apellidos' => 'Lusuardi',
            'email' => 'gestor1@gmail.com',
            'password' => Hash::make('password'),
            'habilitado' => true,
            'id_rol' => $gestorRole->id,
        ]);

        User::create([
            'name' => 'Ian',
            'apellidos' => 'Romero',
            'email' => 'iromero@gmail.com',
            'password' => Hash::make('password'),
            'habilitado' => true,
            'id_rol' => $camareroRole->id,
        ]);

        // Puedes agregar más usuarios según sea necesario
    }
}
