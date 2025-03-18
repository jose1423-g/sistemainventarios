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
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_rol');
            $table->unsignedBigInteger('fk_permiso');
            $table->foreign('fk_rol')->references('id')->on('roles');
            $table->foreign('fk_permiso')->references('id')->on('permisos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rol_permiso', function (Blueprint $table) {
            $table->dropForeign(['fk_rol']);
            $table->dropForeign(['fk_permiso']);
        });
        Schema::dropIfExists('rol_permiso');
    }
};
