<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Dar\Http\Controllers\DarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:dar'])
    ->prefix('app/modules/dar')
    ->group(function (): void {
        Route::get('/', [DarController::class, 'show'])->name('dar.show');
    });
