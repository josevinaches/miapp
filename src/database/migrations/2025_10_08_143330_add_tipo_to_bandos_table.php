<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Solo añadir si NO existe
        if (! Schema::hasColumn('bandos', 'tipo')) {
            Schema::table('bandos', function (Blueprint $table) {
                $table->string('tipo');
            });
        }
        // Si ya existía, no hacemos nada (no-op)
    }

    public function down(): void
    {
        // Solo borrar si SÍ existe
        if (Schema::hasColumn('bandos', 'tipo')) {
            Schema::table('bandos', function (Blueprint $table) {
                $table->dropColumn('tipo');
            });
        }
    }
};
