<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Localisation\Http\Controllers\LocalisationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:localisation'])
    ->prefix('app/modules/localisation')
    ->group(function (): void {
        Route::get('/', [LocalisationController::class, 'show'])->name('localisation.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:localisation'])
    ->prefix('api/v1/localisation')
    ->group(function (): void {
        Route::get('/', [LocalisationController::class, 'show'])->name('api.localisation.show');
    });
