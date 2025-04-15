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
        Schema::table('salidas', function (Blueprint $table) {
            $table->renameColumn('no_compra', 'fk_no_compra');
            $table->renameColumn('area', 'fk_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->renameColumn('fk_no_compra', 'no_compra');
            $table->renameColumn('fk_area', 'area');
        });
    }
};
