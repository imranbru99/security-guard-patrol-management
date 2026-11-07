<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Services;

use App\Modules\Incidents\Models\Incident;
use App\Modules\Incidents\Models\IncidentTimeline;
use InvalidArgumentException;

final class IncidentWorkflow
{
    /**
     * @var array<string, list<string>>
     */
    private const TRANSITIONS = [
        'reported' => ['triaged', 'closed'],
        'triaged' => ['in_progress', 'closed'],
        'in_progress' => ['resolved', 'closed'],
        'resolved' => ['closed', 'reopened'],
        'closed' => ['reopened'],
        'reopened' => ['triaged', 'in_progress', 'closed'],
    ];

    public function transition(Incident $incident, string $toStatus, ?string $note = null, ?int $actorGuardId = null): Incident
    {
        $from = (string) $incident->status;
        $allowed = self::TRANSITIONS[$from] ?? [];

        if (! in_array($toStatus, $allowed, true)) {
            throw new InvalidArgumentException("Cannot transition incident from [{$from}] to [{$toStatus}].");
        }

        $incident->status = $toStatus;

        match ($toStatus) {
            'triaged' => $incident->triaged_at ??= now(),
            'resolved' => $incident->resolved_at ??= now(),
            'closed' => $incident->closed_at ??= now(),
            'reopened' => $incident->closed_at = null,
            default => null,
        };

        $incident->save();

        IncidentTimeline::query()->create([
            'tenant_id' => $incident->tenant_id,
            'incident_id' => $incident->id,
            'actor_guard_id' => $actorGuardId,
            'event' => 'status_changed',
            'body' => $note ?? "Status changed from {$from} to {$toStatus}",
            'occurred_at' => now(),
            'meta' => [
                'from' => $from,
                'to' => $toStatus,
            ],
        ]);

        return $incident->refresh();
    }

    /**
     * @return list<string>
     */
    public function allowedTransitions(Incident $incident): array
    {
        return self::TRANSITIONS[(string) $incident->status] ?? [];
    }
}
