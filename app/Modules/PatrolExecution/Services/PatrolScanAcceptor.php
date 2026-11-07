<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Services;

use App\Core\Support\Geo;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use App\Modules\PatrolExecution\Models\PatrolScan;
use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolRoutes\Models\RouteCheckpoint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Accepts a patrol scan with anti-cheat rules and offline idempotency.
 */
final class PatrolScanAcceptor
{
    /**
     * @param  array{
     *     client_uuid: string,
     *     checkpoint_id?: int,
     *     checkpoint_ulid?: string,
     *     latitude?: float|null,
     *     longitude?: float|null,
     *     mock_location?: bool,
     *     device_id?: string|null,
     *     notes?: string|null,
     *     photo_path?: string|null,
     *     scanned_at?: string|null,
     *     verification_type?: string|null,
     *     meta?: array<string, mixed>|null
     * }  $payload
     */
    public function accept(PatrolTour $tour, array $payload): PatrolScan
    {
        $clientUuid = trim((string) ($payload['client_uuid'] ?? ''));
        if ($clientUuid === '') {
            throw new InvalidPatrolScanException('client_uuid is required.');
        }

        $existing = PatrolScan::query()
            ->where('tenant_id', $tour->tenant_id)
            ->where('client_uuid', $clientUuid)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        if (! $tour->isActive()) {
            throw new InvalidPatrolScanException('Tour is not active.');
        }

        $checkpoint = $this->resolveCheckpoint($tour, $payload);
        $routeCheckpoint = RouteCheckpoint::query()
            ->where('patrol_route_id', $tour->patrol_route_id)
            ->where('checkpoint_id', $checkpoint->id)
            ->first();

        if ($routeCheckpoint === null) {
            throw new InvalidPatrolScanException('Checkpoint is not on this tour route.');
        }

        $route = $tour->patrolRoute;
        if ($route === null) {
            throw new InvalidPatrolScanException('Tour route is missing.');
        }

        $this->assertSequence($tour, $route->is_ordered, $routeCheckpoint);
        $this->assertGps($checkpoint, $route->gps_radius_meters, $payload);

        $rawScannedAt = $payload['scanned_at'] ?? null;
        $scannedAt = is_string($rawScannedAt) && $rawScannedAt !== ''
            ? Carbon::parse($rawScannedAt)
            : now();

        return DB::transaction(function () use ($tour, $checkpoint, $payload, $clientUuid, $scannedAt): PatrolScan {
            $again = PatrolScan::query()
                ->where('tenant_id', $tour->tenant_id)
                ->where('client_uuid', $clientUuid)
                ->lockForUpdate()
                ->first();

            if ($again !== null) {
                return $again;
            }

            return PatrolScan::query()->create([
                'tenant_id' => $tour->tenant_id,
                'patrol_tour_id' => $tour->id,
                'checkpoint_id' => $checkpoint->id,
                'guard_id' => $tour->guard_id,
                'client_uuid' => $clientUuid,
                'scanned_at' => $scannedAt,
                'latitude' => $payload['latitude'] ?? null,
                'longitude' => $payload['longitude'] ?? null,
                'verification_type' => $payload['verification_type'] ?? $checkpoint->verification_type,
                'mock_location' => (bool) ($payload['mock_location'] ?? false),
                'device_id' => $payload['device_id'] ?? null,
                'notes' => $payload['notes'] ?? null,
                'photo_path' => $payload['photo_path'] ?? null,
                'meta' => $payload['meta'] ?? null,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveCheckpoint(PatrolTour $tour, array $payload): Checkpoint
    {
        $query = Checkpoint::query()->where('tenant_id', $tour->tenant_id);

        if (! empty($payload['checkpoint_id'])) {
            $checkpoint = $query->where('id', (int) $payload['checkpoint_id'])->first();
        } elseif (! empty($payload['checkpoint_ulid'])) {
            $checkpoint = $query->where('ulid', (string) $payload['checkpoint_ulid'])->first();
        } else {
            throw new InvalidPatrolScanException('checkpoint_id or checkpoint_ulid is required.');
        }

        if ($checkpoint === null) {
            throw new InvalidPatrolScanException('Checkpoint not found.');
        }

        return $checkpoint;
    }

    private function assertSequence(PatrolTour $tour, bool $isOrdered, RouteCheckpoint $routeCheckpoint): void
    {
        if (! $isOrdered) {
            return;
        }

        $scannedCheckpointIds = PatrolScan::query()
            ->where('patrol_tour_id', $tour->id)
            ->pluck('checkpoint_id')
            ->all();

        $next = RouteCheckpoint::query()
            ->where('patrol_route_id', $tour->patrol_route_id)
            ->whereNotIn('checkpoint_id', $scannedCheckpointIds)
            ->orderBy('sequence')
            ->first();

        if ($next === null) {
            throw new InvalidPatrolScanException('All route checkpoints already scanned.');
        }

        if ((int) $next->checkpoint_id !== (int) $routeCheckpoint->checkpoint_id) {
            throw new InvalidPatrolScanException('Checkpoint out of sequence for ordered route.');
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function assertGps(Checkpoint $checkpoint, int $routeRadiusMeters, array $payload): void
    {
        if ($checkpoint->latitude === null || $checkpoint->longitude === null) {
            return;
        }

        $lat = $payload['latitude'] ?? null;
        $lng = $payload['longitude'] ?? null;
        if (! is_numeric($lat) || ! is_numeric($lng)) {
            throw new InvalidPatrolScanException('GPS coordinates are required for this checkpoint.');
        }

        $radius = $checkpoint->radius_meters > 0
            ? (float) $checkpoint->radius_meters
            : (float) $routeRadiusMeters;

        $ok = Geo::withinRadius(
            (float) $checkpoint->latitude,
            (float) $checkpoint->longitude,
            (float) $lat,
            (float) $lng,
            $radius,
        );

        if (! $ok) {
            throw new InvalidPatrolScanException('Scan GPS is outside the allowed radius.');
        }
    }
}
