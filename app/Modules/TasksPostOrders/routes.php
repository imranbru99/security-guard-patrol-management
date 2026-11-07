<?php

declare(strict_types=1);

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\TasksPostOrders\Http\Controllers\TasksPostOrdersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', SetTenantFromUser::class, 'module:tasks_post_orders'])
    ->prefix('app/modules/tasks-post-orders')
    ->group(function (): void {
        Route::get('/', [TasksPostOrdersController::class, 'show'])->name('tasks-post-orders.show');
    });

Route::middleware(['api', 'auth:sanctum', SetTenantFromUser::class, 'module:tasks_post_orders'])
    ->prefix('api/v1/task-items')
    ->group(function (): void {
        Route::get('/', [TasksPostOrdersController::class, 'index'])->name('api.task-items.index');
    });
