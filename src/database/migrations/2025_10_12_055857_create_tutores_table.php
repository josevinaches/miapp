<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tutores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festero_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->string('dni', 15)->nullable();   // puedes validar luego con tu regla
            $table->string('parentesco')->nullable(); // padre/madre/tutor legal
            $table->string('telefono', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tutores');
    }
};
