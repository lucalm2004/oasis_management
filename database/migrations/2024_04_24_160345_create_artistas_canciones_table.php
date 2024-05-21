<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artistas_canciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_artista');
            $table->unsignedBigInteger('id_cancion');
            $table->timestamps();

            $table->foreign('id_artista')->references('id')->on('artistas')->onDelete('cascade');
            $table->foreign('id_cancion')->references('id')->on('canciones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('artistas_canciones');
    }
};
