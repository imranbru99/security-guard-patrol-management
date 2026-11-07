<?php

declare(strict_types=1);

namespace App\Modules\RecruitmentAts\Http\Controllers;

use App\Modules\RecruitmentAts\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RecruitmentController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'recruitment',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $applications = Application::query()->orderBy('pipeline_stage')->get([
            'ulid', 'applicant_id', 'pipeline_stage', 'position', 'applied_at',
        ]);

        return response()->json(['data' => $applications]);
    }

    /**
     * Stub for a future public apply endpoint (tenant microsite).
     * Currently still behind auth+module gate when hit via tenant routes.
     */
    public function applyStub(Request $request): JsonResponse
    {
        return response()->json([
            'module' => 'recruitment',
            'message' => 'Public apply will be enabled for tenant microsites later.',
            'accepted' => false,
        ]);
    }
}
