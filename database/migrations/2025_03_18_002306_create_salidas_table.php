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
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->string('no_salida')->nullable();
            $table->string('no_compra')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->string('area')->nullable();
            $table->string('personal')->nullable();
            $table->string('producto')->nullable();
            $table->integer('piezas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
