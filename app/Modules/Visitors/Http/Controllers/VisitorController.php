<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Http\Controllers;

use App\Modules\Visitors\Models\Visit;
use App\Modules\Visitors\Services\VisitorBadgePdf;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class VisitorController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'visitors',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = Visit::query()->with('visitor:id,ulid,full_name')->orderByDesc('id')->limit(100)->get([
            'ulid', 'visitor_id', 'site_id', 'host_name', 'status', 'badge_code', 'checked_in_at', 'checked_out_at',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function badge(string $visit, VisitorBadgePdf $pdf): Response
    {
        $model = Visit::query()->where('ulid', $visit)->firstOrFail();

        return $pdf->download($model);
    }
}
