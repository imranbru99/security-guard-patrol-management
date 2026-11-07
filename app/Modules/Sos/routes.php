<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Sos\Http\Controllers\SosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:sos'])
    ->prefix('app/modules/sos')
    ->group(function (): void {
        Route::get('/', [SosController::class, 'show'])->name('sos.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:sos'])
    ->prefix('api/v1/sos')
    ->group(function (): void {
        Route::get('/', [SosController::class, 'index'])->name('api.sos.index');
        Route::post('/trigger', [SosController::class, 'trigger'])->name('api.sos.trigger');
    });
