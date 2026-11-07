<?php

declare(strict_types=1);

namespace App\Modules\PayrollExport\Http\Controllers;

use App\Modules\PayrollExport\Models\PayrollRun;
use Illuminate\Http\JsonResponse;

final class PayrollExportController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'payroll_export',
            'status' => 'enabled',
            'note' => 'Export-ready only — not statutory payroll.',
        ]);
    }

    public function index(): JsonResponse
    {
        $runs = PayrollRun::query()->orderByDesc('period_start')->get([
            'ulid', 'period_start', 'period_end', 'status', 'exported_at',
        ]);

        return response()->json(['data' => $runs]);
    }
}
