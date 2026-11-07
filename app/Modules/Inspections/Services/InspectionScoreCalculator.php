<?php

declare(strict_types=1);

namespace App\Modules\Inspections\Services;

use App\Modules\Inspections\Models\Inspection;
use App\Modules\Inspections\Models\InspectionResult;
use Illuminate\Support\Collection;

final class InspectionScoreCalculator
{
    /**
     * @param  Collection<int, InspectionResult>|iterable<InspectionResult>  $results
     * @return array{score: float, max_score: float, percent: float, passed_count: int, failed_count: int}
     */
    public function calculate(iterable $results): array
    {
        $score = 0.0;
        $maxScore = 0.0;
        $passed = 0;
        $failed = 0;

        foreach ($results as $result) {
            $itemScore = (float) $result->score;
            $itemMax = (float) $result->max_score;

            $score += $itemScore;
            $maxScore += $itemMax;

            if ($result->passed) {
                $passed++;
            } else {
                $failed++;
            }
        }

        $percent = $maxScore > 0.0 ? round(($score / $maxScore) * 100, 2) : 0.0;

        return [
            'score' => round($score, 2),
            'max_score' => round($maxScore, 2),
            'percent' => $percent,
            'passed_count' => $passed,
            'failed_count' => $failed,
        ];
    }

    public function applyToInspection(Inspection $inspection): Inspection
    {
        $totals = $this->calculate($inspection->results()->get());

        $inspection->forceFill([
            'score' => $totals['score'],
            'max_score' => $totals['max_score'],
            'meta' => array_merge($inspection->meta ?? [], [
                'score_percent' => $totals['percent'],
                'passed_count' => $totals['passed_count'],
                'failed_count' => $totals['failed_count'],
            ]),
        ])->save();

        return $inspection->refresh();
    }
}
