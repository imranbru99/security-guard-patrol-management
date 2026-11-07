<?php

declare(strict_types=1);

namespace App\Modules\TrainingCertification\Http\Controllers;

use App\Modules\TrainingCertification\Models\Course;
use Illuminate\Http\JsonResponse;

final class TrainingController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'training',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $courses = Course::query()->orderBy('title')->get([
            'ulid', 'title', 'code', 'duration_hours', 'is_active',
        ]);

        return response()->json(['data' => $courses]);
    }
}
