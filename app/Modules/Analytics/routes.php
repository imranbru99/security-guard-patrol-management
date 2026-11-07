<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Analytics\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:analytics'])
    ->prefix('app/modules/analytics')
    ->group(function (): void {
        Route::get('/', [AnalyticsController::class, 'show'])->name('analytics.show');
    });
