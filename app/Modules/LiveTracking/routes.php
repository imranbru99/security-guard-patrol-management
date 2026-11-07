<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\LiveTracking\Http\Controllers\LiveTrackingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:live_tracking'])
    ->prefix('app/modules/live-tracking')
    ->group(function (): void {
        Route::get('/', [LiveTrackingController::class, 'show'])->name('live-tracking.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:live_tracking'])
    ->prefix('api/v1/tracking')
    ->group(function (): void {
        Route::post('/ping', [LiveTrackingController::class, 'ping'])->name('api.tracking.ping');
    });
