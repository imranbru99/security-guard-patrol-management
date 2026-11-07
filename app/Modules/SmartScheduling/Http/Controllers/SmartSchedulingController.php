<?php

declare(strict_types=1);

namespace App\Modules\SmartScheduling\Http\Controllers;

use App\Core\Tenancy\TenantContext;
use App\Modules\SmartScheduling\Services\CoverageGapPredictor;
use Illuminate\Http\JsonResponse;

final class SmartSchedulingController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'smart_scheduling',
            'status' => 'enabled',
        ]);
    }

    public function forecast(CoverageGapPredictor $predictor): JsonResponse
    {
        $tenant = TenantContext::get();
        abort_if($tenant === null, 404);

        $run = $predictor->run($tenant);

        return response()->json([
            'module' => 'smart_scheduling',
            'forecast_ulid' => $run->ulid,
            'summary' => $run->summary,
            'gaps' => $run->gaps,
        ]);
    }
}
