<?php

declare(strict_types=1);

use App\Modules\Inspections\Models\InspectionResult;
use App\Modules\Inspections\Services\InspectionScoreCalculator;

it('calculates inspection score totals and percent', function (): void {
    $results = collect([
        new InspectionResult(['score' => 8, 'max_score' => 10, 'passed' => true]),
        new InspectionResult(['score' => 2, 'max_score' => 10, 'passed' => false]),
        new InspectionResult(['score' => 5, 'max_score' => 5, 'passed' => true]),
    ]);

    $totals = (new InspectionScoreCalculator)->calculate($results);

    expect($totals['score'])->toBe(15.0)
        ->and($totals['max_score'])->toBe(25.0)
        ->and($totals['percent'])->toBe(60.0)
        ->and($totals['passed_count'])->toBe(2)
        ->and($totals['failed_count'])->toBe(1);
});
