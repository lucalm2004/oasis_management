<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            // 'apellidos' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => null,
            'id_rol' => $AdminRole->id,
        ]);

        User::create([
            'name' => 'David',
            // 'apellidos' => 'Hernández',
            'email' => 'dhernandez@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => null,
            'id_rol' => $userRole->id,
        ]);

        User::create([
            'name' => 'Sergi',
            // 'apellidos' => 'Marín',
            'email' => 'smarin@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => null,
            'id_rol' => $gestorRole->id,
        ]);

        User::create([
            'name' => 'Luca',
            // 'apellidos' => 'Lusuardi',
            'email' => 'gestor1@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => '49902352B',
            'id_rol' => $gestorRole->id,
        ]);

        User::create([
            'name' => 'Ivan',
            // 'apellidos' => 'Rubio',
            'email' => 'gestor2@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => '49902352B',
            'id_rol' => $gestorRole->id,
        ]);

        User::create([
            'name' => 'Ian',
            // 'apellidos' => 'Romero',
            'email' => 'iromero@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => null,
            'id_rol' => $camareroRole->id,
        ]);
        User::create([
            'id' => '999999',
            'name' => 'ChatControler',
            // 'apellidos' => 'Romero',
            'email' => 'ChatControler@gmail.com',
            'password' => bcrypt('password'),
            'habilitado' => true,
            'verificado' => true,
            'DNI' => null,
            'id_rol' => $userRole->id,
        ]);


       

    }
}
