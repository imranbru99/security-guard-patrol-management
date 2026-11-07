<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Jobs;

use App\Modules\Privacy\Models\DsarRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Stub erasure workflow — marks DSAR as processing/completed without deleting production data yet.
 */
final class ErasureJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $dsarRequestId) {}

    public function handle(): void
    {
        $dsar = DsarRequest::query()->find($this->dsarRequestId);
        if ($dsar === null) {
            return;
        }

        $dsar->update([
            'status' => 'completed',
            'completed_at' => now(),
            'meta' => array_merge($dsar->meta ?? [], [
                'erasure_stub' => true,
                'processed_at' => now()->toIso8601String(),
            ]),
        ]);
    }
}
