<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\DisciplinePerformance\Http\Controllers\DisciplineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:discipline'])
    ->prefix('app/modules/discipline')
    ->group(function (): void {
        Route::get('/', [DisciplineController::class, 'show'])->name('discipline.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:discipline'])
    ->prefix('api/v1/discipline')
    ->group(function (): void {
        Route::get('/', [DisciplineController::class, 'index'])->name('api.discipline.index');
    });
