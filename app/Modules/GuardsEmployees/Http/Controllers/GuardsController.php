<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Http\Controllers;

use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Http\JsonResponse;

final class GuardsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'guards',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $guards = Guard::query()->orderBy('name')->get([
            'ulid', 'employee_id', 'name', 'status', 'branch_id', 'badge_qr',
        ]);

        return response()->json(['data' => $guards]);
    }
}
