<?php

declare(strict_types=1);

use App\Modules\ShiftScheduling\Services\AutoScheduler;
use App\Modules\ShiftScheduling\Services\RosterConflictEngine;

it('greedily fills open coverage preferring conflict-free guards', function (): void {
    $scheduler = new AutoScheduler(new RosterConflictEngine(minRestHours: 8.0), seed: 42);

    $result = $scheduler->schedule(
        requirements: [
            [
                'id' => 100,
                'post_id' => 1,
                'starts_at' => '2026-11-08 08:00:00',
                'ends_at' => '2026-11-08 16:00:00',
                'guards_required' => 2,
                'filled' => 0,
            ],
        ],
        guards: [
            ['id' => 1, 'name' => 'Alpha'],
            ['id' => 2, 'name' => 'Bravo'],
            ['id' => 3, 'name' => 'Charlie'],
        ],
        existingAssignments: [],
    );

    expect($result['assignments'])->toHaveCount(2)
        ->and($result['unfilled'])->toBeEmpty();

    $guardIds = collect($result['assignments'])->pluck('guard_id')->unique()->sort()->values()->all();
    expect($guardIds)->toHaveCount(2);
});

it('returns unfilled with reasons when all guards have hard conflicts', function (): void {
    $scheduler = new AutoScheduler(new RosterConflictEngine(minRestHours: 8.0), seed: 1);

    $result = $scheduler->schedule(
        requirements: [
            [
                'id' => 200,
                'post_id' => 9,
                'starts_at' => '2026-11-08 10:00:00',
                'ends_at' => '2026-11-08 18:00:00',
                'guards_required' => 1,
            ],
        ],
        guards: [
            ['id' => 5, 'name' => 'Busy'],
        ],
        existingAssignments: [
            [
                'id' => 1,
                'guard_id' => 5,
                'starts_at' => '2026-11-08 08:00:00',
                'ends_at' => '2026-11-08 16:00:00',
            ],
        ],
    );

    expect($result['assignments'])->toBeEmpty()
        ->and($result['unfilled'])->toHaveCount(1)
        ->and($result['unfilled'][0]['needed'])->toBe(1)
        ->and($result['unfilled'][0]['reason'])->toContain('hard conflict');
});

it('is deterministic for the same seed', function (): void {
    $requirements = [[
        'id' => 1,
        'starts_at' => '2026-11-08 08:00:00',
        'ends_at' => '2026-11-08 12:00:00',
        'guards_required' => 1,
    ]];
    $guards = [
        ['id' => 10],
        ['id' => 20],
        ['id' => 30],
    ];

    $a = (new AutoScheduler(seed: 99))->schedule($requirements, $guards);
    $b = (new AutoScheduler(seed: 99))->schedule($requirements, $guards);

    expect($a['assignments'])->toEqual($b['assignments']);
});
