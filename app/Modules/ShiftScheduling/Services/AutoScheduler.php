<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Services;

use App\Modules\ShiftScheduling\DTOs\ConflictViolation;

/**
 * Greedy, seedable auto-scheduler that fills open coverage requirements.
 *
 * Prefers guards that introduce no hard conflicts with existing + tentative
 * assignments. Deterministic given the same seed and input order.
 */
final class AutoScheduler
{
    public function __construct(
        private readonly RosterConflictEngine $conflictEngine = new RosterConflictEngine,
        private readonly ?int $seed = null,
    ) {}

    /**
     * @param  list<array{
     *     id: int|string,
     *     post_id?: int|null,
     *     starts_at: string,
     *     ends_at: string,
     *     guards_required: int,
     *     filled?: int
     * }>  $requirements
     * @param  list<array{id: int, name?: string}>  $guards
     * @param  list<array{
     *     id?: int|null,
     *     guard_id: int,
     *     starts_at: string,
     *     ends_at: string
     * }>  $existingAssignments
     * @return array{
     *     assignments: list<array{
     *         coverage_requirement_id: int|string,
     *         guard_id: int,
     *         starts_at: string,
     *         ends_at: string,
     *         post_id: int|null
     *     }>,
     *     unfilled: list<array{
     *         coverage_requirement_id: int|string,
     *         needed: int,
     *         reason: string
     *     }>
     * }
     */
    public function schedule(
        array $requirements,
        array $guards,
        array $existingAssignments = [],
    ): array {
        $working = $existingAssignments;
        $newAssignments = [];
        $unfilled = [];

        $orderedRequirements = $requirements;
        usort(
            $orderedRequirements,
            static fn (array $a, array $b): int => strcmp((string) $a['starts_at'], (string) $b['starts_at'])
                ?: ((int) $a['id'] <=> (int) $b['id'])
        );

        $orderedGuards = $this->orderGuards($guards);

        foreach ($orderedRequirements as $requirement) {
            $needed = max(0, (int) $requirement['guards_required'] - (int) ($requirement['filled'] ?? 0));
            if ($needed === 0) {
                continue;
            }

            $assignedForReq = 0;
            $rejectionReasons = [];

            foreach ($orderedGuards as $guard) {
                if ($assignedForReq >= $needed) {
                    break;
                }

                $guardId = (int) $guard['id'];
                $candidate = [
                    'id' => null,
                    'guard_id' => $guardId,
                    'starts_at' => (string) $requirement['starts_at'],
                    'ends_at' => (string) $requirement['ends_at'],
                ];

                $probe = array_merge($working, [$candidate]);
                $hard = array_values(array_filter(
                    $this->conflictEngine->analyze($probe),
                    static fn (ConflictViolation $v): bool => $v->isHard() && $v->guardId === $guardId
                ));

                if ($hard !== []) {
                    $codes = array_unique(array_map(
                        static fn (ConflictViolation $v): string => $v->code,
                        $hard
                    ));
                    $rejectionReasons[] = sprintf(
                        'guard#%d hard conflict (%s)',
                        $guardId,
                        implode(',', $codes)
                    );

                    continue;
                }

                $assignment = [
                    'coverage_requirement_id' => $requirement['id'],
                    'guard_id' => $guardId,
                    'starts_at' => (string) $requirement['starts_at'],
                    'ends_at' => (string) $requirement['ends_at'],
                    'post_id' => isset($requirement['post_id']) ? (int) $requirement['post_id'] : null,
                ];

                $newAssignments[] = $assignment;
                $working[] = $candidate;
                $assignedForReq++;
            }

            $remaining = $needed - $assignedForReq;
            if ($remaining > 0) {
                $reason = $orderedGuards === []
                    ? 'no guards available'
                    : ($rejectionReasons !== []
                        ? 'insufficient conflict-free guards: '.implode('; ', array_slice($rejectionReasons, 0, 5))
                        : 'insufficient guards');

                $unfilled[] = [
                    'coverage_requirement_id' => $requirement['id'],
                    'needed' => $remaining,
                    'reason' => $reason,
                ];
            }
        }

        return [
            'assignments' => $newAssignments,
            'unfilled' => $unfilled,
        ];
    }

    /**
     * @param  list<array{id: int, name?: string}>  $guards
     * @return list<array{id: int, name?: string}>
     */
    private function orderGuards(array $guards): array
    {
        $copy = array_values($guards);

        usort($copy, static fn (array $a, array $b): int => (int) $a['id'] <=> (int) $b['id']);

        if ($this->seed === null) {
            return $copy;
        }

        // Deterministic shuffle via Fisher–Yates with seeded LCG.
        $state = $this->seed & 0x7FFFFFFF;
        $n = count($copy);
        for ($i = $n - 1; $i > 0; $i--) {
            $state = (1103515245 * $state + 12345) & 0x7FFFFFFF;
            $j = $state % ($i + 1);
            [$copy[$i], $copy[$j]] = [$copy[$j], $copy[$i]];
        }

        return $copy;
    }
}
