<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\MediaVault\Http\Controllers\MediaVaultController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:media_vault'])
    ->prefix('app/modules/media-vault')
    ->group(function (): void {
        Route::get('/', [MediaVaultController::class, 'show'])->name('media-vault.show');
    });

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:media_vault', 'signed'])
    ->prefix('app/modules/media-vault')
    ->group(function (): void {
        Route::get('/download/{ulid}', [MediaVaultController::class, 'download'])
            ->name('media-vault.download');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:media_vault'])
    ->prefix('api/v1/media-vault')
    ->group(function (): void {
        Route::get('/', [MediaVaultController::class, 'show'])->name('api.media-vault.show');
    });
