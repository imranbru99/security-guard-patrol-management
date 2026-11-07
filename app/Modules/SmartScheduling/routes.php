<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\SmartScheduling\Http\Controllers\SmartSchedulingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:smart_scheduling'])
    ->prefix('app/modules/smart-scheduling')
    ->group(function (): void {
        Route::get('/', [SmartSchedulingController::class, 'show'])->name('smart-scheduling.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:smart_scheduling'])
    ->prefix('api/v1/smart-scheduling')
    ->group(function (): void {
        Route::post('/forecast', [SmartSchedulingController::class, 'forecast'])->name('api.smart-scheduling.forecast');
    });
