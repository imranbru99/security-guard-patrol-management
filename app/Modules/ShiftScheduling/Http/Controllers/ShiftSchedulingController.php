<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Http\Controllers;

use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Http\JsonResponse;

final class ShiftSchedulingController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'shift_scheduling',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $shifts = Shift::query()->orderByDesc('starts_at')->limit(100)->get([
            'ulid', 'site_id', 'post_id', 'starts_at', 'ends_at', 'status', 'label',
        ]);

        return response()->json(['data' => $shifts]);
    }
}
