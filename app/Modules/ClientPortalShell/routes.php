<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ClientPortalShell\Http\Controllers\ClientPortalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:client_portal'])
    ->prefix('app/modules/client-portal')
    ->group(function (): void {
        Route::get('/', [ClientPortalController::class, 'show'])->name('client-portal.show');
    });

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:client_portal'])
    ->prefix('client/modules/client-portal')
    ->group(function (): void {
        Route::get('/status', [ClientPortalController::class, 'show'])->name('client-portal.status');
    });
