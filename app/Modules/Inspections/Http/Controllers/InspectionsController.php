<?php

declare(strict_types=1);

namespace App\Modules\Inspections\Http\Controllers;

use App\Modules\Inspections\Models\Inspection;
use Illuminate\Http\JsonResponse;

final class InspectionsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'inspections',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = Inspection::query()->orderByDesc('id')->limit(100)->get([
            'ulid', 'title', 'status', 'score', 'max_score', 'site_id', 'completed_at',
        ]);

        return response()->json(['data' => $rows]);
    }
}
