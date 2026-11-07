<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Expenses\Http\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:expenses'])
    ->prefix('app/modules/expenses')
    ->group(function (): void {
        Route::get('/', [ExpensesController::class, 'show'])->name('expenses.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:expenses'])
    ->prefix('api/v1/expenses')
    ->group(function (): void {
        Route::get('/', [ExpensesController::class, 'index'])->name('api.expenses.index');
    });
