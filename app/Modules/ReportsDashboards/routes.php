<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ReportsDashboards\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:reports'])
    ->prefix('app/modules/reports')
    ->group(function (): void {
        Route::get('/', [ReportsController::class, 'show'])->name('reports.show');
        Route::get('/export.csv', [ReportsController::class, 'exportCsv'])->name('reports.export.csv');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:reports'])
    ->prefix('api/v1/reports')
    ->group(function (): void {
        Route::get('/', [ReportsController::class, 'show'])->name('api.reports.show');
        Route::get('/export.csv', [ReportsController::class, 'exportCsv'])->name('api.reports.export.csv');
    });
