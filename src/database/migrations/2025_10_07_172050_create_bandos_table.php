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
        Schema::create('bandos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // 'Moro', 'Cristiano'
            $table->string('slug')->unique();   // 'moro', 'cristiano'
            $table->string('tipo'); // Campo para almacenar "Moro" o "Cristiano"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bandos');
    }
};
