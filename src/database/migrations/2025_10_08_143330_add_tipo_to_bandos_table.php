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
        Schema::table('bandos', function (Blueprint $table) {
            $table->string('tipo'); // Agregar el campo 'tipo'
        });
    }

    public function down(): void
    {
        Schema::table('bandos', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
