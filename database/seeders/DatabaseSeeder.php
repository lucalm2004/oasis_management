<?php

namespace Database\Seeders;

use App\Models\Artista;
use App\Models\ArtistaCancion;
use App\Models\Cancion;
use App\Models\PlaylistCancion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CiudadSeeder::class);
        $this->call(DiscotecaSeeder::class);
        $this->call(UserDiscotecasSeeder::class);
        $this->call(EventoSeeder::class);
        $this->call(BonificacionSeeder::class);
        // $this->call(UserBonificacionSeeder::class);
        // $this->call(ValoracionSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(TipoEntradaSeeder::class);
        $this->call(CancionSeeder::class);
        $this->call(ArtistasSeeder::class);
        $this->call(ArtistasCancionesSeeder::class);
        $this->call(PlaylistsCancionesSeeder::class);
        // $this->call(CarritoSeeder::class);
    }
}
