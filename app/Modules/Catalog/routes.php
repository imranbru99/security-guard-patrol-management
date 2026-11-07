<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Catalog\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:catalog'])
    ->prefix('app/modules/catalog')
    ->group(function (): void {
        Route::get('/', [CatalogController::class, 'show'])->name('catalog.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:catalog'])
    ->prefix('api/v1/catalog')
    ->group(function (): void {
        Route::get('/items', [CatalogController::class, 'index'])->name('api.catalog.items');
    });

Route::middleware(['api'])
    ->prefix('api/v1/public/{tenantSlug}/catalog')
    ->group(function (): void {
        Route::get('/items', [CatalogController::class, 'publicList'])->name('catalog.public.items');
    });
