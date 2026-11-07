<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Services;

use App\Modules\LiveTracking\Models\LocationPing;
use App\Modules\LiveTracking\Models\TrackingSession;
use Illuminate\Support\Carbon;
use RuntimeException;

final class LocationPingAcceptor
{
    /**
     * @param  array{
     *     latitude: float,
     *     longitude: float,
     *     accuracy_meters?: int|null,
     *     battery_percent?: int|null,
     *     recorded_at?: string|null
     * }  $payload
     */
    public function accept(TrackingSession $session, array $payload): LocationPing
    {
        $at = isset($payload['recorded_at']) && $payload['recorded_at']
            ? Carbon::parse((string) $payload['recorded_at'])
            : now();

        if (! $session->allowsPingAt($at)) {
            throw new RuntimeException('Tracking is only allowed during an active shift window.');
        }

        return LocationPing::query()->create([
            'tenant_id' => $session->tenant_id,
            'tracking_session_id' => $session->id,
            'guard_id' => $session->guard_id,
            'latitude' => $payload['latitude'],
            'longitude' => $payload['longitude'],
            'accuracy_meters' => $payload['accuracy_meters'] ?? null,
            'battery_percent' => $payload['battery_percent'] ?? null,
            'recorded_at' => $at,
        ]);
    }
}
