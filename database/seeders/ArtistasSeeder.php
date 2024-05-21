<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artista;

class ArtistasSeeder extends Seeder
{
    public function run()
    {
        Artista::insert([
            ['id' => 1, 'name' => 'Anuel AA', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 2, 'name' => 'Dei V', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 3, 'name' => 'JC Reyes', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 4, 'name' => 'Myke Towers', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 5, 'name' => 'Mora', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 6, 'name' => 'Anonymous', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-05-16 06:55:48'],
            ['id' => 7, 'name' => 'Bryant Myers', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 8, 'name' => 'Lary Over', 'created_at' => '2024-04-25 12:56:09', 'updated_at' => '2024-04-25 12:56:09'],
            ['id' => 10, 'name' => 'Bad Bunny', 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'name' => 'Dei V', 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'name' => 'Aissa', 'created_at' => '2024-05-16 07:01:02', 'updated_at' => '2024-05-16 07:01:02']
        ]);
    }
}
