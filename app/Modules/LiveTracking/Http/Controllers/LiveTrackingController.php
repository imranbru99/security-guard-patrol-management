<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Http\Controllers;

use App\Modules\LiveTracking\Models\TrackingSession;
use App\Modules\LiveTracking\Services\LocationPingAcceptor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class LiveTrackingController
{
    public function __construct(
        private readonly LocationPingAcceptor $acceptor,
    ) {}

    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'live_tracking',
            'status' => 'enabled',
        ]);
    }

    public function ping(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_ulid' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'accuracy_meters' => ['nullable', 'integer', 'min:0'],
            'battery_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $session = TrackingSession::query()->where('ulid', $validated['session_ulid'])->first();
        if ($session === null) {
            return response()->json(['message' => 'Tracking session not found.'], 404);
        }

        try {
            $ping = $this->acceptor->accept($session, $validated);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => [
                'ulid' => $ping->ulid,
                'tracking_session_id' => $ping->tracking_session_id,
                'latitude' => $ping->latitude,
                'longitude' => $ping->longitude,
                'recorded_at' => $ping->recorded_at?->toIso8601String(),
            ],
        ], 201);
    }
}
