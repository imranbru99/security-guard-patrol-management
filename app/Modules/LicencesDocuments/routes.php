<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\LicencesDocuments\Http\Controllers\LicencesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:licences'])
    ->prefix('app/modules/licences')
    ->group(function (): void {
        Route::get('/', [LicencesController::class, 'show'])->name('licences.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:licences'])
    ->prefix('api/v1/licences')
    ->group(function (): void {
        Route::get('/', [LicencesController::class, 'index'])->name('api.licences.index');
    });
