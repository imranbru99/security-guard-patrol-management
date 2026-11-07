<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\PayrollExport\Http\Controllers\PayrollExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:payroll_export'])
    ->prefix('app/modules/payroll-export')
    ->group(function (): void {
        Route::get('/', [PayrollExportController::class, 'show'])->name('payroll-export.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:payroll_export'])
    ->prefix('api/v1/payroll-export')
    ->group(function (): void {
        Route::get('/', [PayrollExportController::class, 'index'])->name('api.payroll-export.index');
    });
