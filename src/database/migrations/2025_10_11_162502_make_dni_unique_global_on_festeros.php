<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('festeros', function (Blueprint $table) {
            // Quita únicos previos por comparsa si existen
            try { $table->dropUnique(['comparsa_id','dni']); } catch (\Throwable $e) {}
            // Único global en dni (permite múltiples NULL en MySQL)
            $table->unique('dni', 'festeros_dni_unique');
        });
    }
    public function down(): void
    {
        Schema::table('festeros', function (Blueprint $table) {
            try { $table->dropUnique('festeros_dni_unique'); } catch (\Throwable $e) {}
            // (opcional) restaurar el anterior si lo tenías
            // $table->unique(['comparsa_id','dni']);
        });
    }
};
