<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bonificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('descripcion', 80);
            $table->integer('puntos');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonificaciones');
    }
};
