<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Http\Controllers;

use App\Core\Tenancy\TenantContext;
use App\Modules\Analytics\Services\KpiCalculator;
use Illuminate\Http\JsonResponse;

final class AnalyticsController
{
    public function show(KpiCalculator $calculator): JsonResponse
    {
        $tenant = TenantContext::get();
        $metrics = $tenant ? $calculator->compute($tenant) : [];

        return response()->json([
            'module' => 'analytics',
            'status' => 'enabled',
            'metrics' => $metrics,
        ]);
    }
}
