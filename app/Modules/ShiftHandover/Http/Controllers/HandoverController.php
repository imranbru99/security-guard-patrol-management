<?php

declare(strict_types=1);

namespace App\Modules\ShiftHandover\Http\Controllers;

use App\Modules\ShiftHandover\Models\Handover;
use Illuminate\Http\JsonResponse;

final class HandoverController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'handover',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = Handover::query()->orderByDesc('id')->limit(100)->get([
            'ulid', 'shift_id', 'from_guard_id', 'to_guard_id', 'status', 'handed_over_at', 'acknowledged_at',
        ]);

        return response()->json(['data' => $rows]);
    }
}
