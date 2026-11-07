<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Http\Controllers;

use App\Modules\CheckpointsTags\Models\Checkpoint;
use Illuminate\Http\JsonResponse;

final class CheckpointsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'checkpoints',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $checkpoints = Checkpoint::query()->orderBy('name')->get([
            'ulid', 'site_id', 'name', 'code', 'verification_type', 'latitude', 'longitude', 'radius_meters', 'is_active',
        ]);

        return response()->json(['data' => $checkpoints]);
    }
}
