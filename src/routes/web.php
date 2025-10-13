<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentanteController;

use App\Http\Controllers\AdminComparsaController;
use App\Http\Controllers\Representante\FesteroController;
use App\Http\Controllers\Representante\CuotaController;
use App\Http\Controllers\AdminTutorController;
use App\Http\Controllers\DashboardRedirectController;

Route::view('/', 'welcome')->name('welcome');

Route::middleware(['auth:web'])->group(function () {

    // Redirección por rol (apto para route:cache)
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');

    // Área común (Admin o Representante)
    Route::middleware(['role:Admin|Representante'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('expedientes', ExpedienteController::class);
    });

    /**
     * SOLO ADMIN
     */
    Route::prefix('admin')->name('admin.')->middleware(['role:Admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // Asignar / Desasignar representante ⇄ comparsa
        Route::get('/comparsas/asignar', [AdminComparsaController::class, 'assignForm'])
            ->name('comparsas.assign');
        Route::post('/comparsas/asignar', [AdminComparsaController::class, 'assignStore'])
            ->name('comparsas.assign.store');
        Route::patch('/comparsas/{comparsa}/desasignar', [AdminComparsaController::class, 'unassign'])
            ->name('comparsas.unassign');

        // Listados auxiliares
        Route::get('/comparsas/{comparsa}/festeros', [AdminComparsaController::class, 'festeros'])
            ->name('comparsas.festeros');
        Route::get('/comparsas/{comparsa}/menores', [AdminComparsaController::class, 'menores'])
            ->name('comparsas.menores');

        // Tutores (Admin) ─ crear / guardar / borrar
        Route::get('festeros/{festero}/tutores/create', [AdminTutorController::class, 'create'])
            ->name('tutores.create');
        Route::post('festeros/{festero}/tutores', [AdminTutorController::class, 'store'])
            ->name('tutores.store');
        Route::delete('tutores/{tutor}', [AdminTutorController::class, 'destroy'])
            ->name('tutores.destroy');

        Route::get('/revisiones', [\App\Http\Controllers\AdminRevisionController::class, 'index'])
            ->name('revisiones.index');
        Route::patch('/revisiones/{revision}', [\App\Http\Controllers\AdminRevisionController::class, 'actualizar'])
            ->name('revisiones.actualizar');
    });

    /**
     * SOLO REPRESENTANTE
     */
    Route::prefix('representante')->name('representante.')->middleware(['role:Representante'])->group(function () {
        Route::get('/', [RepresentanteController::class, 'index'])->name('dashboard');
        Route::get('festeros/export/pdf', [\App\Http\Controllers\Representante\ExportController::class, 'festerosPdf'])
            ->name('festeros.export.pdf');
        Route::post('revisiones/enviar', [\App\Http\Controllers\Representante\RevisionController::class, 'enviar'])
            ->name('revisiones.enviar');

        // CRUD Festeros
        Route::resource('festeros', FesteroController::class);

        // Validación DNI/NIE (AJAX)
        Route::get('api/validate-dni', [FesteroController::class, 'validateDni'])
            ->name('festeros.validate_dni')
            ->middleware('throttle:20,1');

        // 💶 Cuotas por festero
        Route::get('festeros/{festero}/cuota', [CuotaController::class, 'edit'])
            ->name('festeros.cuota.edit');
        Route::post('festeros/{festero}/cuota', [CuotaController::class, 'update'])
            ->name('festeros.cuota.update');

        // Menores (flujo especial: festero + tutor en una sola pantalla)
        Route::get('menores/create', [\App\Http\Controllers\Representante\FesteroController::class, 'createMinor'])
            ->name('menores.create');
        Route::post('menores', [\App\Http\Controllers\Representante\FesteroController::class, 'storeMinor'])
            ->name('menores.store');
        Route::post('festeros/cuotas/apply', [\App\Http\Controllers\Representante\CuotaController::class, 'applyMass'])
            ->name('festeros.cuota.apply');
    });
});

require __DIR__ . '/auth.php';
