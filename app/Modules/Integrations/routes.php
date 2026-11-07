<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Integrations\Http\Controllers\IntegrationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:integrations'])
    ->prefix('app/modules/integrations')
    ->group(function (): void {
        Route::get('/', [IntegrationsController::class, 'show'])->name('integrations.show');
    });

Route::middleware(['web'])
    ->get('/feeds/{tenantSlug}/shifts.ics', [IntegrationsController::class, 'icsFeed'])
    ->name('integrations.shifts.ics');
