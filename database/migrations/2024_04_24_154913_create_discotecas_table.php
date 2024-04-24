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
        Schema::create('discotecas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('direccion', 80);
            $table->string('image', 20);
            $table->string('lat', 45);
            $table->string('long', 45);
            $table->integer('capacidad');
            $table->foreignId('id_ciudad')->constrained('ciudades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discotecas');
    }
};
