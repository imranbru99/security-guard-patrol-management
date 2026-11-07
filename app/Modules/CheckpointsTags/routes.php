<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\CheckpointsTags\Http\Controllers\CheckpointsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:checkpoints'])
    ->prefix('app/modules/checkpoints')
    ->group(function (): void {
        Route::get('/', [CheckpointsController::class, 'show'])->name('checkpoints.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:checkpoints'])
    ->prefix('api/v1/checkpoints')
    ->group(function (): void {
        Route::get('/', [CheckpointsController::class, 'index'])->name('api.checkpoints.index');
    });
