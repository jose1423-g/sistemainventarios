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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->string('no_orden')->nullable();
            $table->string('fk_proveedor')->nullable();
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_entrada')->nullable();
            $table->string('area_solicitante')->nullable();
            $table->string('numero_requisicion')->nullable();
            $table->integer('cantidad_piezas')->nullable();
            $table->double('precio_unitario')->nullable();
            $table->double('IVA')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
