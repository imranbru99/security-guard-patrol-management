<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class ControlRoomController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'control_room',
            'status' => 'enabled',
        ]);
    }
}
