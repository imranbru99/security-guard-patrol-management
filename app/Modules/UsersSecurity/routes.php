<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:users_security'])
    ->prefix('app/modules/users-security')
    ->group(function (): void {
        Route::get('/', fn () => response('Users, Roles & Security module', 200))
            ->name('users-security.show');
    });
