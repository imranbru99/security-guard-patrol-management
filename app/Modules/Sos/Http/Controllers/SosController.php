<?php

declare(strict_types=1);

namespace App\Modules\Sos\Http\Controllers;

use App\Modules\Sos\Models\SosEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SosController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'sos',
            'status' => 'enabled',
        ]);
    }

    public function trigger(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'guard_id' => ['nullable', 'integer'],
            'site_id' => ['nullable', 'integer'],
            'shift_id' => ['nullable', 'integer'],
            'kind' => ['nullable', 'string', 'max:50'],
            'silent' => ['nullable', 'boolean'],
            'drill_mode' => ['nullable', 'boolean'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $event = SosEvent::query()->create([
            'guard_id' => $validated['guard_id'] ?? null,
            'site_id' => $validated['site_id'] ?? null,
            'shift_id' => $validated['shift_id'] ?? null,
            'kind' => $validated['kind'] ?? 'panic',
            'silent' => (bool) ($validated['silent'] ?? false),
            'drill_mode' => (bool) ($validated['drill_mode'] ?? false),
            'status' => 'open',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'triggered_at' => now(),
        ]);

        return response()->json([
            'data' => [
                'ulid' => $event->ulid,
                'status' => $event->status,
                'kind' => $event->kind,
                'triggered_at' => $event->triggered_at?->toIso8601String(),
            ],
        ], 201);
    }

    public function index(): JsonResponse
    {
        $rows = SosEvent::query()->orderByDesc('triggered_at')->limit(100)->get([
            'ulid', 'guard_id', 'site_id', 'kind', 'silent', 'drill_mode', 'status', 'triggered_at',
        ]);

        return response()->json(['data' => $rows]);
    }
}
