<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Http\Controllers;

use App\Modules\DisciplinePerformance\Models\DisciplinaryRecord;
use Illuminate\Http\JsonResponse;

final class DisciplineController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'discipline',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $records = DisciplinaryRecord::query()->orderByDesc('occurred_on')->get([
            'ulid', 'guard_id', 'severity', 'title', 'occurred_on', 'outcome',
        ]);

        return response()->json(['data' => $records]);
    }
}
