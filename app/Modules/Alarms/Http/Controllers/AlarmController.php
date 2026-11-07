<?php

declare(strict_types=1);

namespace App\Modules\Alarms\Http\Controllers;

use App\Modules\Alarms\Models\Alarm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AlarmController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'alarms',
            'status' => 'enabled',
        ]);
    }

    public function inbound(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'external_id' => ['nullable', 'string', 'max:191'],
            'site_id' => ['nullable', 'integer'],
            'alarm_type' => ['nullable', 'string', 'max:100'],
            'severity' => ['nullable', 'string', 'max:50'],
            'sla_seconds' => ['nullable', 'integer', 'min:0'],
            'payload' => ['nullable', 'array'],
        ]);

        $alarm = Alarm::query()->create([
            'external_id' => $validated['external_id'] ?? null,
            'site_id' => $validated['site_id'] ?? null,
            'source' => 'webhook',
            'alarm_type' => $validated['alarm_type'] ?? 'generic',
            'severity' => $validated['severity'] ?? 'medium',
            'status' => 'received',
            'received_at' => now(),
            'sla_seconds' => $validated['sla_seconds'] ?? 900,
            'payload' => $validated['payload'] ?? $request->all(),
        ]);

        return response()->json([
            'data' => [
                'ulid' => $alarm->ulid,
                'status' => $alarm->status,
                'sla_seconds' => $alarm->sla_seconds,
                'received_at' => $alarm->received_at?->toIso8601String(),
            ],
        ], 201);
    }
}
