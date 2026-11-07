<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ClientsSites\Http\Controllers\ClientsSitesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:clients_sites'])
    ->prefix('app/modules/clients-sites')
    ->group(function (): void {
        Route::get('/', [ClientsSitesController::class, 'show'])->name('clients-sites.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:clients_sites'])
    ->prefix('api/v1')
    ->group(function (): void {
        Route::get('/clients', [ClientsSitesController::class, 'clients'])->name('api.clients.index');
        Route::get('/sites', [ClientsSitesController::class, 'sites'])->name('api.sites.index');
    });
