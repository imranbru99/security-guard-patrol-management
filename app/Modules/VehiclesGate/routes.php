<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\VehiclesGate\Http\Controllers\VehiclesGateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:vehicles_gate'])
    ->prefix('app/modules/vehicles-gate')
    ->group(function (): void {
        Route::get('/', [VehiclesGateController::class, 'show'])->name('vehicles-gate.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:vehicles_gate'])
    ->prefix('api/v1/vehicle-logs')
    ->group(function (): void {
        Route::get('/', [VehiclesGateController::class, 'index'])->name('api.vehicle-logs.index');
    });
