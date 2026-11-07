<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ShiftHandover\Http\Controllers\HandoverController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:handover'])
    ->prefix('app/modules/handover')
    ->group(function (): void {
        Route::get('/', [HandoverController::class, 'show'])->name('handover.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:handover'])
    ->prefix('api/v1/handovers')
    ->group(function (): void {
        Route::get('/', [HandoverController::class, 'index'])->name('api.handovers.index');
    });
