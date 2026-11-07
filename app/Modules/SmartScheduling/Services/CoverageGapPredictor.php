<?php

declare(strict_types=1);

namespace App\Modules\SmartScheduling\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\ShiftScheduling\Models\Shift;
use App\Modules\SmartScheduling\Models\ForecastRun;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

final class CoverageGapPredictor
{
    public function run(Tenant $tenant, string $horizon = '7d'): ForecastRun
    {
        $days = max(1, (int) filter_var($horizon, FILTER_SANITIZE_NUMBER_INT) ?: 7);
        $from = Carbon::now()->subDays(28)->startOfDay();
        $to = Carbon::now()->endOfDay();

        $historyCounts = [];
        if (class_exists(Shift::class) && Schema::hasTable('shifts')) {
            $rows = Shift::query()
                ->where('tenant_id', $tenant->id)
                ->whereBetween('starts_at', [$from, $to])
                ->selectRaw('DATE(starts_at) as day, COUNT(*) as c')
                ->groupBy('day')
                ->pluck('c', 'day');

            foreach ($rows as $day => $count) {
                $historyCounts[(string) $day] = (int) $count;
            }
        }

        $avg = count($historyCounts) > 0
            ? array_sum($historyCounts) / count($historyCounts)
            : 0.0;

        $gaps = [];
        for ($i = 1; $i <= $days; $i++) {
            $day = Carbon::now()->addDays($i)->toDateString();
            $expected = (int) round($avg);
            $projected = $expected;
            if ($expected < 1 && count($historyCounts) === 0) {
                $gaps[] = [
                    'date' => $day,
                    'severity' => 'unknown',
                    'message' => 'Insufficient history to forecast coverage',
                    'expected_shifts' => 0,
                ];
            } elseif ($expected < 2) {
                $gaps[] = [
                    'date' => $day,
                    'severity' => 'low',
                    'message' => 'Low historical coverage may leave posts understaffed',
                    'expected_shifts' => $projected,
                ];
            }
        }

        return ForecastRun::query()->create([
            'tenant_id' => $tenant->id,
            'horizon' => $horizon,
            'ran_at' => now(),
            'status' => 'completed',
            'inputs' => [
                'history_from' => $from->toDateString(),
                'history_to' => $to->toDateString(),
                'day_counts' => $historyCounts,
                'average_daily_shifts' => $avg,
            ],
            'gaps' => $gaps,
            'summary' => [
                'gap_count' => count($gaps),
                'average_daily_shifts' => round($avg, 2),
            ],
        ]);
    }
}
