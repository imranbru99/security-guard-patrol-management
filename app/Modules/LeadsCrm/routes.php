<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\LeadsCrm\Http\Controllers\LeadsCrmController;
use App\Modules\LeadsCrm\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->group(function (): void {
        Route::post('/quote-request', [QuoteRequestController::class, 'store'])
            ->name('leads.quote-request');
    });

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:leads_crm'])
    ->prefix('app/modules/leads-crm')
    ->group(function (): void {
        Route::get('/', [LeadsCrmController::class, 'show'])->name('leads-crm.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:leads_crm'])
    ->prefix('api/v1/leads')
    ->group(function (): void {
        Route::get('/', [LeadsCrmController::class, 'leads'])->name('api.leads.index');
    });
