<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ContractsSlas\Http\Controllers\ContractsSlasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:contracts_slas'])
    ->prefix('app/modules/contracts-slas')
    ->group(function (): void {
        Route::get('/', [ContractsSlasController::class, 'show'])->name('contracts-slas.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:contracts_slas'])
    ->prefix('api/v1/contracts')
    ->group(function (): void {
        Route::get('/', [ContractsSlasController::class, 'index'])->name('api.contracts.index');
    });
