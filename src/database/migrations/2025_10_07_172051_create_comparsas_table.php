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
        Schema::create('comparsas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bando_id')->constrained()->cascadeOnDelete();
            $table->string('nombre')->unique();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete(); // 1:1 con representante
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparsas');
    }
};
