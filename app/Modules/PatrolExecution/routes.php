<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\PatrolExecution\Http\Controllers\PatrolExecutionController;
use App\Modules\PatrolExecution\Http\Controllers\PatrolScanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:patrol_execution'])
    ->prefix('app/modules/patrol-execution')
    ->group(function (): void {
        Route::get('/', [PatrolExecutionController::class, 'show'])->name('patrol-execution.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:patrol_execution'])
    ->prefix('api/v1/patrol')
    ->group(function (): void {
        Route::post('/scan', [PatrolScanController::class, 'store'])->name('api.patrol.scan');
    });
