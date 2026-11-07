<?php

declare(strict_types=1);

namespace App\Modules\Hazards\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class HazardController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'hazards',
            'status' => 'enabled',
        ]);
    }
}
