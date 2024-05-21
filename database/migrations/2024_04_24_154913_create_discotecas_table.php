<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discotecas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('direccion', 80);
            $table->string('image', 255)->nullable();
            $table->string('lat', 45);
            $table->string('long', 45);
            $table->integer('capacidad')->nullable();
            $table->unsignedBigInteger('id_ciudad');
            $table->timestamps();

            $table->foreign('id_ciudad')->references('id')->on('ciudades')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discotecas');
    }
};
