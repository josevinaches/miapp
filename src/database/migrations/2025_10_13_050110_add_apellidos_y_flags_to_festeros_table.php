<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('festeros', function (Blueprint $table) {
            $table->string('primer_apellido')->after('nombre');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');

            $table->boolean('trabuco')->default(false)->after('fecha_nacimiento');
            $table->boolean('embarque')->default(false)->after('trabuco');

            // Opcional: si quieres forzar que al menos 'primer_apellido' no sea vacío a nivel DB, ya está NOT NULL por defecto.
            // La restricción “si es menor no puede trabuco/embarque” la aplicaremos en validación (no en DB).
        });
    }

    public function down(): void
    {
        Schema::table('festeros', function (Blueprint $table) {
            $table->dropColumn(['primer_apellido','segundo_apellido','trabuco','embarque']);
        });
    }
};
