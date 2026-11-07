<?php

declare(strict_types=1);

use App\Modules\KeyControl\Models\KeyLog;
use Carbon\Carbon;

it('flags overdue key logs that are past due and not returned', function (): void {
    $log = new KeyLog([
        'status' => 'issued',
        'due_at' => Carbon::parse('2026-11-01 10:00:00'),
        'returned_at' => null,
    ]);

    expect($log->isOverdue(Carbon::parse('2026-11-08 12:00:00')))->toBeTrue();
});

it('does not flag returned or lost key logs as overdue', function (): void {
    $returned = new KeyLog([
        'status' => 'returned',
        'due_at' => Carbon::parse('2026-11-01 10:00:00'),
        'returned_at' => Carbon::parse('2026-11-01 09:00:00'),
    ]);

    $lost = new KeyLog([
        'status' => 'lost',
        'due_at' => Carbon::parse('2026-11-01 10:00:00'),
        'returned_at' => null,
    ]);

    $now = Carbon::parse('2026-11-08 12:00:00');

    expect($returned->isOverdue($now))->toBeFalse();
    expect($lost->isOverdue($now))->toBeFalse();
});
