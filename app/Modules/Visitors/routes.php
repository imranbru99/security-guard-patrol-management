<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Visitors\Http\Controllers\KioskPreRegisterController;
use App\Modules\Visitors\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('kiosk/{tenantSlug}/visitors')
    ->group(function (): void {
        Route::get('/pre-register', [KioskPreRegisterController::class, 'show'])->name('visitors.kiosk.show');
        Route::post('/pre-register', [KioskPreRegisterController::class, 'store'])->name('visitors.kiosk.store');
    });

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:visitors'])
    ->prefix('app/modules/visitors')
    ->group(function (): void {
        Route::get('/', [VisitorController::class, 'show'])->name('visitors.show');
        Route::get('/visits/{visit}/badge', [VisitorController::class, 'badge'])->name('visitors.badge');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:visitors'])
    ->prefix('api/v1/visits')
    ->group(function (): void {
        Route::get('/', [VisitorController::class, 'index'])->name('api.visits.index');
    });
