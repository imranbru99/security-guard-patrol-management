<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Services;

use App\Modules\ShiftScheduling\DTOs\ConflictViolation;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Deterministic conflict analyser for shift assignments (overlap + rest period).
 *
 * Pure PHP — accepts assignment arrays; does not touch the database.
 */
final class RosterConflictEngine
{
    public function __construct(
        private readonly float $minRestHours = 8.0,
        private readonly string $restSeverity = ConflictViolation::SEVERITY_HARD,
    ) {}

    /**
     * @param  list<array{
     *     id?: int|null,
     *     guard_id: int,
     *     starts_at: string|CarbonInterface,
     *     ends_at: string|CarbonInterface
     * }>  $assignments
     * @return list<ConflictViolation>
     */
    public function analyze(array $assignments): array
    {
        $normalized = [];
        foreach ($assignments as $row) {
            $starts = $this->toCarbon($row['starts_at']);
            $ends = $this->toCarbon($row['ends_at']);

            if ($ends->lessThanOrEqualTo($starts)) {
                continue;
            }

            $normalized[] = [
                'id' => isset($row['id']) ? (int) $row['id'] : null,
                'guard_id' => (int) $row['guard_id'],
                'starts_at' => $starts,
                'ends_at' => $ends,
            ];
        }

        /** @var array<int, list<array{id: int|null, guard_id: int, starts_at: Carbon, ends_at: Carbon}>> $byGuard */
        $byGuard = [];
        foreach ($normalized as $item) {
            $byGuard[$item['guard_id']][] = $item;
        }

        $violations = [];

        foreach ($byGuard as $guardId => $rows) {
            usort(
                $rows,
                static fn (array $a, array $b): int => $a['starts_at']->timestamp <=> $b['starts_at']->timestamp
            );

            $count = count($rows);
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $a = $rows[$i];
                    $b = $rows[$j];

                    if ($this->overlaps($a['starts_at'], $a['ends_at'], $b['starts_at'], $b['ends_at'])) {
                        $violations[] = new ConflictViolation(
                            code: ConflictViolation::CODE_OVERLAP,
                            severity: ConflictViolation::SEVERITY_HARD,
                            guardId: $guardId,
                            assignmentIdA: $a['id'],
                            assignmentIdB: $b['id'],
                            message: 'Guard has overlapping shift assignments.',
                            meta: [
                                'starts_at_a' => $a['starts_at']->toIso8601String(),
                                'ends_at_a' => $a['ends_at']->toIso8601String(),
                                'starts_at_b' => $b['starts_at']->toIso8601String(),
                                'ends_at_b' => $b['ends_at']->toIso8601String(),
                            ],
                        );
                    }
                }
            }

            for ($i = 0; $i < $count - 1; $i++) {
                $current = $rows[$i];
                $next = $rows[$i + 1];

                if ($this->overlaps($current['starts_at'], $current['ends_at'], $next['starts_at'], $next['ends_at'])) {
                    continue;
                }

                $gapHours = abs($current['ends_at']->diffInSeconds($next['starts_at'])) / 3600;

                if ($gapHours < $this->minRestHours) {
                    $violations[] = new ConflictViolation(
                        code: ConflictViolation::CODE_REST_PERIOD,
                        severity: $this->restSeverity,
                        guardId: $guardId,
                        assignmentIdA: $current['id'],
                        assignmentIdB: $next['id'],
                        message: sprintf(
                            'Rest period of %.2fh is below required %.2fh.',
                            $gapHours,
                            $this->minRestHours
                        ),
                        meta: [
                            'gap_hours' => round($gapHours, 4),
                            'min_rest_hours' => $this->minRestHours,
                            'ends_at' => $current['ends_at']->toIso8601String(),
                            'next_starts_at' => $next['starts_at']->toIso8601String(),
                        ],
                    );
                }
            }
        }

        return $violations;
    }

    /**
     * @param  list<array{
     *     id?: int|null,
     *     guard_id: int,
     *     starts_at: string|CarbonInterface,
     *     ends_at: string|CarbonInterface
     * }>  $assignments
     */
    public function hasHardConflicts(array $assignments): bool
    {
        foreach ($this->analyze($assignments) as $violation) {
            if ($violation->isHard()) {
                return true;
            }
        }

        return false;
    }

    private function overlaps(
        CarbonInterface $aStart,
        CarbonInterface $aEnd,
        CarbonInterface $bStart,
        CarbonInterface $bEnd,
    ): bool {
        return $aStart->lt($bEnd) && $bStart->lt($aEnd);
    }

    private function toCarbon(string|CarbonInterface $value): Carbon
    {
        return $value instanceof CarbonInterface
            ? Carbon::instance($value)->utc()
            : Carbon::parse($value)->utc();
    }
}
