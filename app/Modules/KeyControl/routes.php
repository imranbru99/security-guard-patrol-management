<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\KeyControl\Http\Controllers\KeyControlController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:key_control'])
    ->prefix('app/modules/key-control')
    ->group(function (): void {
        Route::get('/', [KeyControlController::class, 'show'])->name('key-control.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:key_control'])
    ->prefix('api/v1/key-items')
    ->group(function (): void {
        Route::get('/', [KeyControlController::class, 'index'])->name('api.key-items.index');
    });
