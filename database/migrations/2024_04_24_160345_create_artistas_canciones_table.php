<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artistas_canciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_artista')->constrained('artistas');
            $table->foreignId('id_cancion')->constrained('canciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artistas_canciones');
    }
};
