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
        Schema::create('tipos_entradas', function (Blueprint $table) {
            $table->foreignId('id')->constrained('productos');
            $table->longText('descripcion');
            $table->decimal('precio', 3);
            $table->foreignId('id_producto')->constrained('productos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_entradas');
    }
};
