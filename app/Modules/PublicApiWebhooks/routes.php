<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\PublicApiWebhooks\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:public_api'])
    ->prefix('app/modules/public-api')
    ->group(function (): void {
        Route::get('/', [WebhookController::class, 'show'])->name('public-api.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:public_api'])
    ->prefix('api/v1')
    ->group(function (): void {
        Route::get('/public-api', [WebhookController::class, 'show'])->name('api.public-api.show');
        Route::post('/webhooks/ping', [WebhookController::class, 'ping'])->name('api.v1.webhooks.ping');
    });
