<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\AiAssistants\Http\Controllers\AiAssistantsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:ai_assistants'])
    ->prefix('app/modules/ai-assistants')
    ->group(function (): void {
        Route::get('/', [AiAssistantsController::class, 'show'])->name('ai-assistants.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:ai_assistants'])
    ->prefix('api/v1/ai-assistants')
    ->group(function (): void {
        Route::post('/incident-draft', [AiAssistantsController::class, 'draftIncident'])->name('api.ai-assistants.incident-draft');
    });
