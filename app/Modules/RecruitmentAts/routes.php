<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\RecruitmentAts\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:recruitment'])
    ->prefix('app/modules/recruitment')
    ->group(function (): void {
        Route::get('/', [RecruitmentController::class, 'show'])->name('recruitment.show');
        Route::get('/apply', [RecruitmentController::class, 'applyStub'])->name('recruitment.apply');
    });

// Reserved public apply path (no auth) for tenant microsite later — module gate optional once tenant resolved.
Route::middleware(['web'])
    ->prefix('apply')
    ->group(function (): void {
        Route::get('/', fn () => response()->json([
            'module' => 'recruitment',
            'message' => 'Public apply reserved for tenant microsite; enable via microsite module later.',
        ]))->name('recruitment.public.apply');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:recruitment'])
    ->prefix('api/v1/recruitment')
    ->group(function (): void {
        Route::get('/', [RecruitmentController::class, 'index'])->name('api.recruitment.index');
    });
