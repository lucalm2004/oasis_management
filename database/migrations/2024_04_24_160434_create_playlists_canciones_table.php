<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('playlists_canciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_evento');
            $table->unsignedBigInteger('id_canciones');
            $table->timestamps();

            $table->foreign('id_evento')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('id_canciones')->references('id')->on('canciones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('playlists_canciones');
    }
};
