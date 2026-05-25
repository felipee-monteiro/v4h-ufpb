<?php

declare(strict_types=1);

use App\Enums\RoleName;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SseController;
use App\Http\Controllers\TeleconsultoriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->as('dashboard.')->group(static function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

Route::redirect('/', '/dashboard')->name('home');
require __DIR__ . '/settings.php';

Route::middleware(['auth', 'role:' . RoleName::SOLICITANTE->value])->prefix('solicitante')->name('solicitante.')->group(static function (): void {
    Route::get('teleconsultorias', [TeleconsultoriaController::class, 'index'])->name('teleconsultorias.index');
    Route::post('teleconsultorias', [TeleconsultoriaController::class, 'store'])->name('teleconsultorias.store');
    Route::get('teleconsultorias/{teleconsultoria}', [TeleconsultoriaController::class, 'show'])->name('teleconsultorias.show');
});

Route::middleware(['auth', 'role:' . RoleName::ESPECIALISTA->value])->prefix('especialista')->name('especialista.')->group(static function (): void {
    Route::post('teleconsultorias/{teleconsultoria}/parecer', [TeleconsultoriaController::class, 'registerOpinion'])
        ->name('teleconsultorias.registerOpinion');
});

Route::middleware('auth')->get('sse/teleconsultorias/stream', [SseController::class, 'stream'])
    ->name('sse.teleconsultorias.stream');
