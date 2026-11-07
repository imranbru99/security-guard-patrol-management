<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Emergency\Http\Controllers\EmergencyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:emergency'])
    ->prefix('app/modules/emergency')
    ->group(function (): void {
        Route::get('/', [EmergencyController::class, 'show'])->name('emergency.show');
    });
