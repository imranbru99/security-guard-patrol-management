<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Assets\Http\Controllers\AssetsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:assets'])
    ->prefix('app/modules/assets')
    ->group(function (): void {
        Route::get('/', [AssetsController::class, 'show'])->name('assets.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:assets'])
    ->prefix('api/v1/assets')
    ->group(function (): void {
        Route::get('/', [AssetsController::class, 'index'])->name('api.assets.index');
    });
