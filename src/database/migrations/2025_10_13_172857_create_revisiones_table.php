<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('revisiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparsa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('representante_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('ejercicio');
            $table->string('archivo_pdf'); // storage path
            $table->enum('estado', ['pendiente','revisado','aprobado','rechazado'])->default('pendiente');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_comentario')->nullable();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('revisiones'); }
};
