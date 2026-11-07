<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Http\Controllers;

use App\Modules\Incidents\Models\Incident;
use App\Modules\Incidents\Services\IncidentWorkflow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class IncidentController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'incidents',
            'status' => 'enabled',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'severity' => ['nullable', 'string', 'in:low,medium,high,critical'],
            'site_id' => ['nullable', 'integer'],
            'post_id' => ['nullable', 'integer'],
            'shift_id' => ['nullable', 'integer'],
            'occurred_at' => ['nullable', 'date'],
            'client_uuid' => ['nullable', 'string', 'max:64'],
        ]);

        if (! empty($validated['client_uuid'])) {
            $existing = Incident::query()
                ->where('meta->client_uuid', $validated['client_uuid'])
                ->first();

            if ($existing !== null) {
                return response()->json([
                    'data' => [
                        'ulid' => $existing->ulid,
                        'status' => $existing->status,
                        'idempotent' => true,
                    ],
                ]);
            }
        }

        $meta = [];
        if (! empty($validated['client_uuid'])) {
            $meta['client_uuid'] = $validated['client_uuid'];
        }

        $incident = Incident::query()->create([
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'severity' => $validated['severity'] ?? 'medium',
            'site_id' => $validated['site_id'] ?? null,
            'post_id' => $validated['post_id'] ?? null,
            'shift_id' => $validated['shift_id'] ?? null,
            'occurred_at' => $validated['occurred_at'] ?? now(),
            'status' => 'reported',
            'meta' => $meta === [] ? null : $meta,
        ]);

        return response()->json([
            'data' => [
                'ulid' => $incident->ulid,
                'status' => $incident->status,
                'idempotent' => false,
            ],
        ], 201);
    }

    public function transition(Request $request, string $ulid, IncidentWorkflow $workflow): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        $incident = Incident::query()->where('ulid', $ulid)->firstOrFail();
        $workflow->transition($incident, $validated['status'], $validated['note'] ?? null);

        return response()->json([
            'data' => [
                'ulid' => $incident->ulid,
                'status' => $incident->fresh()->status,
            ],
        ]);
    }
}
