<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Http\Controllers;

use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolExecution\Services\InvalidPatrolScanException;
use App\Modules\PatrolExecution\Services\PatrolScanAcceptor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PatrolScanController
{
    public function __construct(
        private readonly PatrolScanAcceptor $acceptor,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tour_ulid' => ['required', 'string'],
            'client_uuid' => ['required', 'uuid'],
            'checkpoint_ulid' => ['nullable', 'string'],
            'checkpoint_id' => ['nullable', 'integer'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'mock_location' => ['sometimes', 'boolean'],
            'device_id' => ['nullable', 'string', 'max:128'],
            'notes' => ['nullable', 'string'],
            'photo_path' => ['nullable', 'string', 'max:512'],
            'scanned_at' => ['nullable', 'date'],
            'verification_type' => ['nullable', 'string', 'max:16'],
            'meta' => ['nullable', 'array'],
        ]);

        if (empty($validated['checkpoint_ulid']) && empty($validated['checkpoint_id'])) {
            return response()->json([
                'message' => 'checkpoint_ulid or checkpoint_id is required.',
            ], 422);
        }

        $tour = PatrolTour::query()->where('ulid', $validated['tour_ulid'])->first();
        if ($tour === null) {
            return response()->json(['message' => 'Tour not found.'], 404);
        }

        try {
            $scan = $this->acceptor->accept($tour, $validated);
        } catch (InvalidPatrolScanException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => [
                'ulid' => $scan->ulid,
                'client_uuid' => $scan->client_uuid,
                'checkpoint_id' => $scan->checkpoint_id,
                'scanned_at' => $scan->scanned_at?->toIso8601String(),
                'mock_location' => $scan->mock_location,
            ],
        ], 201);
    }
}
