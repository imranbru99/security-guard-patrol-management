<?php

declare(strict_types=1);

namespace App\Modules\Dar\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class DarController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'dar',
            'status' => 'enabled',
        ]);
    }
}
