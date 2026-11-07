<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\TenancyOrganisation\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:tenancy'])
    ->prefix('app/modules/tenancy')
    ->group(function (): void {
        Route::get('/', fn () => response('Tenancy & Organisation module', 200))
            ->name('tenancy.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:tenancy'])
    ->prefix('api/v1/branches')
    ->group(function (): void {
        Route::get('/', [BranchController::class, 'index'])->name('api.branches.index');
        Route::post('/', [BranchController::class, 'store'])->name('api.branches.store');
        Route::get('/{ulid}', [BranchController::class, 'show'])->name('api.branches.show');
        Route::put('/{ulid}', [BranchController::class, 'update'])->name('api.branches.update');
        Route::delete('/{ulid}', [BranchController::class, 'destroy'])->name('api.branches.destroy');
    });
