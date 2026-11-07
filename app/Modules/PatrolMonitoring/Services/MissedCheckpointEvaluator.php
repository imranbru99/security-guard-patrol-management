<?php

declare(strict_types=1);

namespace App\Modules\PatrolMonitoring\Services;

use App\Modules\PatrolExecution\Models\PatrolScan;
use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolMonitoring\Models\PatrolEvent;
use App\Modules\PatrolRoutes\Models\RouteCheckpoint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Finds overdue checkpoints on active tours and creates PatrolEvent rows idempotently.
 */
final class MissedCheckpointEvaluator
{
    /**
     * @return Collection<int, PatrolEvent>
     */
    public function evaluate(?Carbon $now = null): Collection
    {
        $now ??= now();
        $created = collect();

        $tours = PatrolTour::query()
            ->with('patrolRoute')
            ->where('status', PatrolTour::STATUS_ACTIVE)
            ->whereNotNull('started_at')
            ->get();

        foreach ($tours as $tour) {
            foreach ($this->overdueRouteCheckpoints($tour, $now) as $routeCheckpoint) {
                $event = $this->createMissedEvent($tour, $routeCheckpoint, $now);
                if ($event !== null) {
                    $created->push($event);
                }
            }
        }

        return $created;
    }

    /**
     * @return Collection<int, RouteCheckpoint>
     */
    public function overdueRouteCheckpoints(PatrolTour $tour, Carbon $now): Collection
    {
        $route = $tour->patrolRoute;
        if ($route === null || $tour->started_at === null) {
            return collect();
        }

        $graceMinutes = max(0, (int) $route->grace_minutes);
        $scannedIds = PatrolScan::query()
            ->where('patrol_tour_id', $tour->id)
            ->pluck('checkpoint_id')
            ->all();

        return RouteCheckpoint::query()
            ->where('patrol_route_id', $tour->patrol_route_id)
            ->whereNotIn('checkpoint_id', $scannedIds)
            ->orderBy('sequence')
            ->get()
            ->filter(function (RouteCheckpoint $rc) use ($tour, $now, $graceMinutes): bool {
                $expectedMinutes = $rc->expected_minutes_from_start;
                if ($expectedMinutes === null) {
                    // Default expectation: sequence * 10 minutes when not configured.
                    $expectedMinutes = ((int) $rc->sequence) * 10;
                }

                $dueAt = $tour->started_at->copy()->addMinutes($expectedMinutes + $graceMinutes);

                return $now->greaterThan($dueAt);
            })
            ->values();
    }

    public function createMissedEvent(PatrolTour $tour, RouteCheckpoint $routeCheckpoint, Carbon $now): ?PatrolEvent
    {
        $key = sprintf('missed:%d:%d', $tour->id, $routeCheckpoint->checkpoint_id);

        $existing = PatrolEvent::query()
            ->where('tenant_id', $tour->tenant_id)
            ->where('idempotency_key', $key)
            ->first();

        if ($existing !== null) {
            return null;
        }

        return PatrolEvent::query()->create([
            'tenant_id' => $tour->tenant_id,
            'patrol_tour_id' => $tour->id,
            'checkpoint_id' => $routeCheckpoint->checkpoint_id,
            'type' => PatrolEvent::TYPE_MISSED_CHECKPOINT,
            'idempotency_key' => $key,
            'occurred_at' => $now,
            'payload' => [
                'sequence' => $routeCheckpoint->sequence,
                'expected_minutes_from_start' => $routeCheckpoint->expected_minutes_from_start,
                'grace_minutes' => $tour->patrolRoute?->grace_minutes,
            ],
        ]);
    }
}
