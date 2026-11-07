<?php

declare(strict_types=1);

namespace App\Modules\Assets\Http\Controllers;

use App\Modules\Assets\Models\Asset;
use Illuminate\Http\JsonResponse;

final class AssetsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'assets',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = Asset::query()->orderBy('asset_tag')->limit(100)->get([
            'ulid', 'asset_tag', 'name', 'category', 'status', 'is_armed_equipment', 'custody_discrepancy',
        ]);

        return response()->json(['data' => $rows]);
    }
}
