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
        Schema::table('personal', function (Blueprint $table){
            $table->string('curp')->nullable()->after('nombre');
            $table->string('rfc')->nullable()->after('curp');
            $table->string('puesto')->nullable()->after('rfc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table){
            $table->dropColumn('curp');
            $table->dropColumn('rfc');
            $table->dropColumn('puesto');
        });
    }
};
