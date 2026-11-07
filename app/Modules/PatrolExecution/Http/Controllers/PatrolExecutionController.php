<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class PatrolExecutionController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'patrol_execution',
            'status' => 'enabled',
        ]);
    }
}
