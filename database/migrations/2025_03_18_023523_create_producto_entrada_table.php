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
        Schema::create('producto_entrada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_producto');
            $table->unsignedBigInteger('fk_entrada');
            $table->foreign('fk_producto')->references('id')->on('productos');
            $table->foreign('fk_entrada')->references('id')->on('entradas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_entrada' , function (Blueprint $table) {
            $table->dropForeign(['fk_producto']);
            $table->dropForeign(['fk_entrada']);
        });
        Schema::dropIfExists('producto_entrada');
    }
};
