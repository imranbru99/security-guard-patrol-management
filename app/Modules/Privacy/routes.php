<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Privacy\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:privacy'])
    ->prefix('app/modules/privacy')
    ->group(function (): void {
        Route::get('/', [PrivacyController::class, 'show'])->name('privacy.show');
    });
