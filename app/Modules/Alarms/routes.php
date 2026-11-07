<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Alarms\Http\Controllers\AlarmController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:alarms'])
    ->prefix('app/modules/alarms')
    ->group(function (): void {
        Route::get('/', [AlarmController::class, 'show'])->name('alarms.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:alarms'])
    ->prefix('api/v1/alarms')
    ->group(function (): void {
        Route::post('/inbound', [AlarmController::class, 'inbound'])->name('api.alarms.inbound');
        Route::post('/webhook', [AlarmController::class, 'inbound'])->name('api.alarms.webhook');
    });
