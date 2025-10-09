<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentanteController;
use PHPUnit\Event\Runtime\PHP;

// 1) Landing pública (sin auth)
Route::view('/', 'welcome')->name('welcome');

Route::middleware(['auth:web'])->group(function () {

    // Redirección por rol (Spatie)
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        return $user->hasRole('Admin')
            ? to_route('admin.dashboard')
            : to_route('representante.dashboard');
    })->name('dashboard');

    // Área común (Admin o Representante)
    Route::middleware(['role:Admin|Representante,web'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::resource('expedientes', ExpedienteController::class);
    });

    // Solo Admin
    Route::middleware(['role:Admin,web'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/comparsas/asignar', [\App\Http\Controllers\AdminComparsaController::class, 'assignForm'])->name('admin.comparsas.assign');
        Route::post('/admin/comparsas/asignar', [\App\Http\Controllers\AdminComparsaController::class, 'assignStore'])->name('admin.comparsas.assign.store');
    });

    // Solo Representante
    Route::middleware(['role:Representante'])->group(function () {
        Route::get('/representante', [RepresentanteController::class, 'index'])->name('representante.dashboard');
    });
});



require __DIR__ . '/auth.php';


Route::get('/ping-role', fn()=> 'ok-role')->middleware(['auth:web','role:Representante,web']);
