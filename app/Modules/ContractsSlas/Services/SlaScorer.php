<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Services;

use App\Modules\ContractsSlas\Models\Sla;
use App\Modules\ContractsSlas\Models\SlaScore;

/**
 * Stub scorer — returns a placeholder float until real metrics are wired.
 */
final class SlaScorer
{
    /**
     * @param  array<string, mixed>  $metrics
     */
    public function score(Sla $sla, array $metrics = []): float
    {
        $actual = (float) ($metrics['actual'] ?? $sla->target_value);
        $target = (float) $sla->target_value;

        if ($target <= 0.0) {
            return 0.0;
        }

        $ratio = min(1.0, $actual / $target);

        return round($ratio * 100, 2);
    }

    /**
     * @param  array<string, mixed>  $metrics
     */
    public function record(Sla $sla, array $metrics = [], ?string $notes = null): SlaScore
    {
        $value = $this->score($sla, $metrics);

        return SlaScore::query()->create([
            'tenant_id' => $sla->tenant_id,
            'sla_id' => $sla->id,
            'score' => $value,
            'scored_at' => now(),
            'notes' => $notes,
        ]);
    }
}
