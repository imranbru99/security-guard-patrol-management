<?php

declare(strict_types=1);

namespace App\Modules\Emergency\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class EmergencyController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'emergency',
            'status' => 'enabled',
        ]);
    }
}
