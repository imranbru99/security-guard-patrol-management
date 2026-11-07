<?php

declare(strict_types=1);

use App\Modules\ShiftScheduling\DTOs\ConflictViolation;
use App\Modules\ShiftScheduling\Services\RosterConflictEngine;

it('flags overlapping assignments for the same guard as a hard violation', function (): void {
    $engine = new RosterConflictEngine(minRestHours: 8.0);

    $violations = $engine->analyze([
        [
            'id' => 1,
            'guard_id' => 10,
            'starts_at' => '2026-11-08 08:00:00',
            'ends_at' => '2026-11-08 16:00:00',
        ],
        [
            'id' => 2,
            'guard_id' => 10,
            'starts_at' => '2026-11-08 12:00:00',
            'ends_at' => '2026-11-08 20:00:00',
        ],
    ]);

    expect($violations)->not->toBeEmpty();

    $overlap = collect($violations)->first(
        fn (ConflictViolation $v): bool => $v->code === ConflictViolation::CODE_OVERLAP
    );

    expect($overlap)->not->toBeNull()
        ->and($overlap->severity)->toBe(ConflictViolation::SEVERITY_HARD)
        ->and($overlap->guardId)->toBe(10)
        ->and($overlap->isHard())->toBeTrue();
});

it('does not flag non-overlapping assignments for different guards', function (): void {
    $engine = new RosterConflictEngine;

    $violations = $engine->analyze([
        [
            'id' => 1,
            'guard_id' => 1,
            'starts_at' => '2026-11-08 08:00:00',
            'ends_at' => '2026-11-08 16:00:00',
        ],
        [
            'id' => 2,
            'guard_id' => 2,
            'starts_at' => '2026-11-08 08:00:00',
            'ends_at' => '2026-11-08 16:00:00',
        ],
    ]);

    expect($violations)->toBeEmpty();
});

it('flags rest-period violations when gap is below threshold', function (): void {
    $engine = new RosterConflictEngine(minRestHours: 10.0);

    $violations = $engine->analyze([
        [
            'id' => 1,
            'guard_id' => 7,
            'starts_at' => '2026-11-08 06:00:00',
            'ends_at' => '2026-11-08 14:00:00',
        ],
        [
            'id' => 2,
            'guard_id' => 7,
            'starts_at' => '2026-11-08 18:00:00',
            'ends_at' => '2026-11-09 02:00:00',
        ],
    ]);

    $rest = collect($violations)->first(
        fn (ConflictViolation $v): bool => $v->code === ConflictViolation::CODE_REST_PERIOD
    );

    expect($rest)->not->toBeNull()
        ->and($rest->severity)->toBe(ConflictViolation::SEVERITY_HARD)
        ->and($rest->meta['gap_hours'])->toBeLessThan(10.0);
});
