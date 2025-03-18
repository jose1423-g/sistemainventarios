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
        Schema::create('personal_area', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_personal');
            $table->unsignedBigInteger('fk_area');
            $table->foreign('fk_personal')->references('id')->on('personal');
            $table->foreign('fk_area')->references('id')->on('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_area', function (Blueprint $table) {
            $table->dropForeign(['fk_personal']);
            $table->dropForeign(['fk_area']);
        });
        Schema::dropIfExists('personal_area');
    }
};
