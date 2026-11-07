<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:audit'])
    ->prefix('app/modules/audit')
    ->group(function (): void {
        Route::get('/', fn () => response('Audit, Activity & Compliance module', 200))
            ->name('audit.show');
    });
