<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\PatrolMonitoring\Http\Controllers\PatrolMonitoringController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:patrol_monitoring'])
    ->prefix('app/modules/patrol-monitoring')
    ->group(function (): void {
        Route::get('/', [PatrolMonitoringController::class, 'show'])->name('patrol-monitoring.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:patrol_monitoring'])
    ->prefix('api/v1/patrol-monitoring')
    ->group(function (): void {
        Route::get('/events', [PatrolMonitoringController::class, 'events'])->name('api.patrol-monitoring.events');
    });
