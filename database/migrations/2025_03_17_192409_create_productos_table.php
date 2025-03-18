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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('no_entrada')->nullable();
            $table->string('nombre')->nullable();
            $table->string('no_partida')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('stock')->nullable();
            $table->string('unidad')->nullable();
            $table->double('precio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
