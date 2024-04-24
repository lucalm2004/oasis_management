<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Ciudad::create(['nombre' => 'Barcelona']);
        Ciudad::create(['nombre' => 'Tarragona']);
        Ciudad::create(['nombre' => 'Madrid']);
        Ciudad::create(['nombre' => 'Valencia']);
        Ciudad::create(['nombre' => 'Málaga']);

    }
}
