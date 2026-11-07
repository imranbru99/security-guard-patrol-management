<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\GuardsEmployees\Http\Controllers\GuardsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:guards'])
    ->prefix('app/modules/guards')
    ->group(function (): void {
        Route::get('/', [GuardsController::class, 'show'])->name('guards.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:guards'])
    ->prefix('api/v1/guards')
    ->group(function (): void {
        Route::get('/', [GuardsController::class, 'index'])->name('api.guards.index');
    });
