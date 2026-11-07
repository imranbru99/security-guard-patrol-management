<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\DemoToggle\Http\Controllers\DemoToggleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:demo_toggle'])
    ->prefix('app/modules/demo-toggle')
    ->group(function (): void {
        Route::get('/', [DemoToggleController::class, 'show'])->name('demo-toggle.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:demo_toggle'])
    ->prefix('api/v1/demo-toggle')
    ->group(function (): void {
        Route::get('/', [DemoToggleController::class, 'api'])->name('api.demo-toggle.show');
    });
