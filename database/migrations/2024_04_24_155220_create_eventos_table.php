<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->longText('descripcion');
            $table->string('flyer', 45);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->string('dj', 45);
            $table->string('name_playlist', 45);
            $table->unsignedBigInteger('id_discoteca');
            $table->timestamps();
            $table->string('capacidad', 30)->nullable();
            $table->string('capacidadVip', 30)->nullable();

            $table->foreign('id_discoteca')->references('id')->on('discotecas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
};
