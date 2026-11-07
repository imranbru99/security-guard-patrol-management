<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Microsite\Http\Controllers\MicrositeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:microsite'])
    ->prefix('app/modules/microsite')
    ->group(function (): void {
        Route::get('/', [MicrositeController::class, 'show'])->name('microsite.show');
    });

Route::middleware(['web'])
    ->prefix('site/{tenantSlug}')
    ->group(function (): void {
        Route::get('/', [MicrositeController::class, 'publicSite'])->name('microsite.public.home');
        Route::get('/{pageSlug}', [MicrositeController::class, 'publicSite'])->name('microsite.public.page');
    });
