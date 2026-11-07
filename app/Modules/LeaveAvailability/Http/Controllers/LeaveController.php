<?php

declare(strict_types=1);

namespace App\Modules\LeaveAvailability\Http\Controllers;

use App\Modules\LeaveAvailability\Models\LeaveRequest;
use Illuminate\Http\JsonResponse;

final class LeaveController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'leave',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $requests = LeaveRequest::query()->orderByDesc('starts_on')->get([
            'ulid', 'guard_id', 'leave_type_id', 'starts_on', 'ends_on', 'status',
        ]);

        return response()->json(['data' => $requests]);
    }
}
