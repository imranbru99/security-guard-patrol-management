<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Http\Controllers;

use App\Modules\PatrolRoutes\Models\PatrolRoute;
use Illuminate\Http\JsonResponse;

final class PatrolRoutesController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'patrol_routes',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $routes = PatrolRoute::query()->orderBy('name')->get([
            'ulid', 'site_id', 'name', 'is_ordered', 'randomised', 'grace_minutes', 'gps_radius_meters', 'is_active',
        ]);

        return response()->json(['data' => $routes]);
    }
}
