<?php

declare(strict_types=1);

use App\Enums\RoleName;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeleconsultoriaController;
use App\Http\Controllers\UsersController;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->as('dashboard.')->group(static function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::group(['as' => 'users.', 'prefix' => 'users'], static function (): void {
        Route::get('/', [UsersController::class, 'index'])->name('index');
    });
});

// Route::get('/', static function(
//     #[CurrentUser()] User $currentUser
// ) {

//     $currentUser->assignRole(RoleName::ESPECIALISTA->value);

//     dd($currentUser->roles()->pluck('name'));
// });

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
