<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Inspections\Http\Controllers\InspectionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:inspections'])
    ->prefix('app/modules/inspections')
    ->group(function (): void {
        Route::get('/', [InspectionsController::class, 'show'])->name('inspections.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:inspections'])
    ->prefix('api/v1/inspections')
    ->group(function (): void {
        Route::get('/', [InspectionsController::class, 'index'])->name('api.inspections.index');
    });
