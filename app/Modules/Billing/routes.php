<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Billing\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:billing'])
    ->prefix('app/modules/billing')
    ->group(function (): void {
        Route::get('/', [BillingController::class, 'show'])->name('billing.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:billing'])
    ->prefix('api/v1/billing')
    ->group(function (): void {
        Route::get('/invoices', [BillingController::class, 'index'])->name('api.billing.invoices');
    });
