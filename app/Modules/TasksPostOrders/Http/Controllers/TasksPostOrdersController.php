<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Http\Controllers;

use App\Modules\TasksPostOrders\Models\TaskItem;
use Illuminate\Http\JsonResponse;

final class TasksPostOrdersController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'tasks_post_orders',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = TaskItem::query()->orderByDesc('id')->limit(100)->get([
            'ulid', 'title', 'status', 'priority', 'due_at', 'assigned_guard_id', 'post_id',
        ]);

        return response()->json(['data' => $rows]);
    }
}
