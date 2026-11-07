<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\PatrolRoutes\Http\Controllers\PatrolRoutesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:patrol_routes'])
    ->prefix('app/modules/patrol-routes')
    ->group(function (): void {
        Route::get('/', [PatrolRoutesController::class, 'show'])->name('patrol-routes.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:patrol_routes'])
    ->prefix('api/v1/patrol-routes')
    ->group(function (): void {
        Route::get('/', [PatrolRoutesController::class, 'index'])->name('api.patrol-routes.index');
    });
