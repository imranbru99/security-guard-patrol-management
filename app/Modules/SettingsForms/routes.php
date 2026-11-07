<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\SettingsForms\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:settings_forms'])
    ->prefix('app/modules/settings-forms')
    ->group(function (): void {
        Route::get('/', [FormSubmissionController::class, 'show'])->name('settings-forms.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:settings_forms'])
    ->prefix('api/v1/forms')
    ->group(function (): void {
        Route::get('/', [FormSubmissionController::class, 'show'])->name('api.forms.show');
        Route::post('/{ulid}/submissions', [FormSubmissionController::class, 'store'])
            ->name('api.forms.submissions.store');
    });
