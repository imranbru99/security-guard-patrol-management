<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\LeaveAvailability\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:leave'])
    ->prefix('app/modules/leave')
    ->group(function (): void {
        Route::get('/', [LeaveController::class, 'show'])->name('leave.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:leave'])
    ->prefix('api/v1/leave')
    ->group(function (): void {
        Route::get('/', [LeaveController::class, 'index'])->name('api.leave.index');
    });
