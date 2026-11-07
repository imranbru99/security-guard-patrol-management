<?php

declare(strict_types=1);

namespace App\Modules\Dar\Services;

use App\Modules\Dar\Models\Dar;
use App\Modules\Dar\Models\DarEntry;
use App\Modules\Incidents\Models\Incident;
use App\Modules\PatrolExecution\Models\PatrolScan;
use App\Modules\ShiftScheduling\Models\Shift;
use App\Modules\Visitors\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Compiles a DAR from shift-linked incidents, visitor activity, and patrol scans.
 */
final class DarCompileFromShift
{
    public function compile(Shift $shift, ?int $guardId = null): Dar
    {
        return DB::transaction(function () use ($shift, $guardId): Dar {
            $dar = Dar::query()->create([
                'tenant_id' => $shift->tenant_id,
                'shift_id' => $shift->id,
                'site_id' => $shift->site_id,
                'guard_id' => $guardId,
                'report_date' => $shift->starts_at?->toDateString() ?? now()->toDateString(),
                'status' => 'draft',
                'client_visible' => false,
                'meta' => [
                    'compiled_from' => 'shift',
                    'sources' => ['incidents', 'visits', 'patrol_scans'],
                ],
            ]);

            $entryCount = 0;

            $entryCount += $this->addIncidentEntries($dar, $shift);
            $entryCount += $this->addVisitEntries($dar, $shift);
            $entryCount += $this->addPatrolScanEntries($dar, $shift, $guardId);

            if ($entryCount === 0) {
                DarEntry::query()->create([
                    'tenant_id' => $shift->tenant_id,
                    'dar_id' => $dar->id,
                    'occurred_at' => $shift->starts_at ?? now(),
                    'category' => 'system',
                    'body' => 'No patrol, visitor, or incident activity recorded for this shift yet.',
                    'source' => 'compile_from_shift',
                    'meta' => ['empty' => true],
                ]);
            }

            return $dar->load('entries');
        });
    }

    private function addIncidentEntries(Dar $dar, Shift $shift): int
    {
        if (! class_exists(Incident::class) || ! Schema::hasTable('incidents')) {
            return 0;
        }

        $incidents = Incident::query()
            ->where('tenant_id', $shift->tenant_id)
            ->where(function ($q) use ($shift): void {
                $q->where('shift_id', $shift->id);
                if ($shift->site_id && $shift->starts_at && $shift->ends_at) {
                    $q->orWhere(function ($inner) use ($shift): void {
                        $inner->where('site_id', $shift->site_id)
                            ->whereBetween('occurred_at', [$shift->starts_at, $shift->ends_at]);
                    });
                }
            })
            ->orderBy('occurred_at')
            ->limit(100)
            ->get();

        foreach ($incidents as $incident) {
            DarEntry::query()->create([
                'tenant_id' => $shift->tenant_id,
                'dar_id' => $dar->id,
                'occurred_at' => $incident->occurred_at ?? $incident->reported_at ?? now(),
                'category' => 'incident',
                'body' => sprintf(
                    'Incident [%s] %s (severity: %s, status: %s)',
                    $incident->ulid,
                    $incident->title,
                    $incident->severity,
                    $incident->status,
                ),
                'source' => 'incident',
                'meta' => ['incident_ulid' => $incident->ulid],
            ]);
        }

        return $incidents->count();
    }

    private function addVisitEntries(Dar $dar, Shift $shift): int
    {
        if (! class_exists(Visit::class) || ! Schema::hasTable('visits') || ! $shift->site_id) {
            return 0;
        }

        $query = Visit::query()
            ->where('tenant_id', $shift->tenant_id)
            ->where('site_id', $shift->site_id);

        if ($shift->starts_at && $shift->ends_at) {
            $query->where(function ($q) use ($shift): void {
                $q->whereBetween('checked_in_at', [$shift->starts_at, $shift->ends_at])
                    ->orWhereBetween('created_at', [$shift->starts_at, $shift->ends_at]);
            });
        }

        $visits = $query->orderBy('checked_in_at')->limit(100)->get();

        foreach ($visits as $visit) {
            DarEntry::query()->create([
                'tenant_id' => $shift->tenant_id,
                'dar_id' => $dar->id,
                'occurred_at' => $visit->checked_in_at ?? $visit->created_at ?? now(),
                'category' => 'visitor',
                'body' => sprintf(
                    'Visit %s status=%s',
                    $visit->ulid,
                    $visit->status ?? 'unknown',
                ),
                'source' => 'visit',
                'meta' => ['visit_ulid' => $visit->ulid],
            ]);
        }

        return $visits->count();
    }

    private function addPatrolScanEntries(Dar $dar, Shift $shift, ?int $guardId): int
    {
        if (! class_exists(PatrolScan::class) || ! Schema::hasTable('patrol_scans')) {
            return 0;
        }

        if (! $shift->starts_at || ! $shift->ends_at) {
            return 0;
        }

        $query = PatrolScan::query()
            ->where('tenant_id', $shift->tenant_id)
            ->whereBetween('scanned_at', [$shift->starts_at, $shift->ends_at]);

        if ($guardId !== null) {
            $query->where('guard_id', $guardId);
        }

        $scans = $query->orderBy('scanned_at')->limit(200)->get();

        foreach ($scans as $scan) {
            DarEntry::query()->create([
                'tenant_id' => $shift->tenant_id,
                'dar_id' => $dar->id,
                'occurred_at' => $scan->scanned_at ?? now(),
                'category' => 'patrol',
                'body' => sprintf(
                    'Checkpoint scan %s via %s',
                    $scan->ulid,
                    $scan->verification_type ?? 'unknown',
                ),
                'source' => 'patrol_scan',
                'meta' => [
                    'patrol_scan_ulid' => $scan->ulid,
                    'checkpoint_id' => $scan->checkpoint_id,
                ],
            ]);
        }

        return $scans->count();
    }
}
