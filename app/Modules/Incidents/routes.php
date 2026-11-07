<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Incidents\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:incidents'])
    ->prefix('app/modules/incidents')
    ->group(function (): void {
        Route::get('/', [IncidentController::class, 'show'])->name('incidents.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:incidents'])
    ->prefix('api/v1/incidents')
    ->group(function (): void {
        Route::post('/', [IncidentController::class, 'store'])->name('api.incidents.store');
        Route::post('/{ulid}/transition', [IncidentController::class, 'transition'])->name('api.incidents.transition');
    });
