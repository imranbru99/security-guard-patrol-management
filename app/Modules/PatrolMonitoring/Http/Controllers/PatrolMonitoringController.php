<?php

declare(strict_types=1);

namespace App\Modules\PatrolMonitoring\Http\Controllers;

use App\Modules\PatrolMonitoring\Models\PatrolEvent;
use Illuminate\Http\JsonResponse;

final class PatrolMonitoringController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'patrol_monitoring',
            'status' => 'enabled',
        ]);
    }

    public function events(): JsonResponse
    {
        $events = PatrolEvent::query()->orderByDesc('occurred_at')->limit(100)->get([
            'ulid', 'patrol_tour_id', 'checkpoint_id', 'type', 'idempotency_key', 'occurred_at',
        ]);

        return response()->json(['data' => $events]);
    }
}
