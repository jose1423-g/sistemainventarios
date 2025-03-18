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
        Schema::create('permiso_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_permiso');
            $table->unsignedBigInteger('fk_usuario');
            $table->foreign('fk_permiso')->references('id')->on('permisos');
            $table->foreign('fk_usuario')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permiso_usuario', function (Blueprint $table) {
            $table->dropForeign(['fk_permiso']);
            $table->dropForeign(['fk_usuario']);
        });
        Schema::dropIfExists('permiso_usuario');
    }
};
