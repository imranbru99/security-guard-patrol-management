<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ControlRoom\Http\Controllers\ControlRoomController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:control_room'])
    ->prefix('app/modules/control-room')
    ->group(function (): void {
        Route::get('/', [ControlRoomController::class, 'show'])->name('control-room.show');
    });
