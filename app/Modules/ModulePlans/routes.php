<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ModulePlans\Http\Controllers\ModuleToggleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:module_plans'])
    ->prefix('app/modules/module-plans')
    ->group(function (): void {
        Route::get('/', fn () => response('Module & Plan Manager module', 200))
            ->name('module-plans.show');
        Route::post('/toggle', ModuleToggleController::class)
            ->name('module-plans.toggle');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:module_plans'])
    ->prefix('api/v1/modules')
    ->group(function (): void {
        Route::post('/toggle', ModuleToggleController::class)->name('api.modules.toggle');
    });
