<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', fn () => response()->json(['ok' => true, 'service' => 'guardops']))
        ->name('api.v1.health');

    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
        return $request->user();
    })->name('api.v1.me');
});
