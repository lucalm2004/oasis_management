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
        Schema::create('registro_entradas', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->length(11); // ID de 11 dígitos
            $table->unsignedBigInteger('evento_id'); // Relación con la tabla eventos
            $table->integer('total_entradas'); // Total de entradas
            $table->decimal('precio_total', 10, 2); // Precio total
            $table->date('fecha'); // Fecha
            $table->tinyInteger('tipo_entrada');// Relación con la tabla eventos

            // Claves foráneas y restricciones
            $table->foreign('evento_id')->references('id')->on('eventos');
          

            // Timestamps (opcional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_entradas');
    }
};
