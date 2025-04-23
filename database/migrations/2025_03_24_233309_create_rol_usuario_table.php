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
        Schema::create('rol_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_rol');
            $table->unsignedBigInteger('fk_usuario');
            $table->foreign('fk_rol')->references('id')->on('roles');
            $table->foreign('fk_usuario')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rol_usuario', function (Blueprint $table) {
            $table->dropForeign(['fk_rol']);
            $table->dropForeign(['fk_usuario']);
        });
        Schema::dropIfExists('rol_usuario');
    }
};
