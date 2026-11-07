<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Hazards\Http\Controllers\HazardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:hazards'])
    ->prefix('app/modules/hazards')
    ->group(function (): void {
        Route::get('/', [HazardController::class, 'show'])->name('hazards.show');
    });
