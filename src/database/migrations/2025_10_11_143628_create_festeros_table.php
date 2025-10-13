<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('festeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparsa_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->string('dni')->nullable();              // opcional
            $table->string('email')->nullable();            // opcional
            $table->string('telefono')->nullable();         // opcional
            $table->date('fecha_nacimiento')->nullable();   // opcional
            $table->timestamps();

            // Evita duplicados básicos por comparsa (ajustable)
            $table->unique(['comparsa_id','dni']);
            $table->unique(['comparsa_id','email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festeros');
    }
};
