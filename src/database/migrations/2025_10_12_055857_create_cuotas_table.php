<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festero_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('ejercicio'); // p.ej. 2025
            $table->decimal('cuota_asociacion', 10, 2)->default(0);
            $table->decimal('cuota_fester', 10, 2)->default(0);
            // pagos (puedes ampliarlo luego)
            $table->boolean('pagada_asociacion')->default(false);
            $table->boolean('pagada_fester')->default(false);
            $table->timestamp('pagada_asociacion_at')->nullable();
            $table->timestamp('pagada_fester_at')->nullable();
            $table->timestamps();

            $table->unique(['festero_id','ejercicio']); // 1 registro por año y festero
        });
    }

    public function down(): void {
        Schema::dropIfExists('cuotas');
    }
};
