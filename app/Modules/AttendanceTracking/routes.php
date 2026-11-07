<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\AttendanceTracking\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:attendance'])
    ->prefix('app/modules/attendance')
    ->group(function (): void {
        Route::get('/', [AttendanceController::class, 'show'])->name('attendance.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:attendance'])
    ->prefix('api/v1/attendance')
    ->group(function (): void {
        Route::get('/summaries', [AttendanceController::class, 'index'])->name('api.attendance.summaries');
        Route::post('/virtual-checkin', [AttendanceController::class, 'virtualCheckIn'])->name('api.attendance.virtual-checkin');
    });
