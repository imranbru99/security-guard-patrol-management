<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\MobilePwa\Http\Controllers\MobilePwaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:mobile_pwa'])
    ->prefix('app/modules/mobile-pwa')
    ->group(function (): void {
        Route::get('/', [MobilePwaController::class, 'show'])->name('mobile-pwa.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:mobile_pwa'])
    ->prefix('api/v1/mobile')
    ->group(function (): void {
        Route::post('/offline-sync', [MobilePwaController::class, 'sync'])->name('api.mobile.offline-sync');
    });

// Public PWA assets (also under public/ for production web servers).
Route::get('/manifest.webmanifest', [MobilePwaController::class, 'manifest'])
    ->name('mobile-pwa.manifest');
Route::get('/service-worker.js', [MobilePwaController::class, 'serviceWorker'])
    ->name('mobile-pwa.service-worker');
