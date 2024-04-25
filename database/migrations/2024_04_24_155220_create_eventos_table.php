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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->longText('descripcion');
            $table->string('flyer', 45)->binary();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->string('dj', 45);
            $table->string('name_playlist', 45);
            $table->foreignId('id_discoteca')->constrained('discotecas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
