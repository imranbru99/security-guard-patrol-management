<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ShiftScheduling\Http\Controllers\ShiftSchedulingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:shift_scheduling'])
    ->prefix('app/modules/shift-scheduling')
    ->group(function (): void {
        Route::get('/', [ShiftSchedulingController::class, 'show'])->name('shift_scheduling.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:shift_scheduling'])
    ->prefix('api/v1/shifts')
    ->group(function (): void {
        Route::get('/', [ShiftSchedulingController::class, 'index'])->name('api.shifts.index');
    });
