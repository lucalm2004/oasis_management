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

        Ciudad::create(['name' => 'Tarragona']);
        Ciudad::create(['name' => 'Valencia']);
        Ciudad::create(['name' => 'Barcelona']);
        Ciudad::create(['name' => 'Málaga']);
    
    }
}
