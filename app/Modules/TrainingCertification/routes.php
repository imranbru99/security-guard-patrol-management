<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\TrainingCertification\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:training'])
    ->prefix('app/modules/training')
    ->group(function (): void {
        Route::get('/', [TrainingController::class, 'show'])->name('training.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:training'])
    ->prefix('api/v1/training')
    ->group(function (): void {
        Route::get('/', [TrainingController::class, 'index'])->name('api.training.index');
    });
