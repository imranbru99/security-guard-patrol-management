<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Http\Controllers;

use App\Modules\VehiclesGate\Models\VehicleLog;
use Illuminate\Http\JsonResponse;

final class VehiclesGateController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'vehicles_gate',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = VehicleLog::query()->orderByDesc('id')->limit(100)->get([
            'ulid', 'plate_number', 'driver_name', 'direction', 'entered_at', 'exited_at', 'violation',
        ]);

        return response()->json(['data' => $rows]);
    }
}
