<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users_discotecas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_discoteca')->nullable();
            $table->unsignedBigInteger('id_users')->nullable();
            $table->timestamps();

            $table->foreign('id_discoteca')->references('id')->on('discotecas')->onDelete('cascade');
            $table->foreign('id_users')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_discotecas');
    }
};
