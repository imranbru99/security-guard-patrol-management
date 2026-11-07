<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:notifications'])
    ->prefix('app/modules/notifications')
    ->group(function (): void {
        Route::get('/', fn () => response('Notifications & Messaging module', 200))
            ->name('notifications.show');
    });
