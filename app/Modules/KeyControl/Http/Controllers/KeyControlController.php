<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Http\Controllers;

use App\Modules\KeyControl\Models\KeyItem;
use Illuminate\Http\JsonResponse;

final class KeyControlController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'key_control',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = KeyItem::query()->orderBy('code')->limit(100)->get([
            'ulid', 'code', 'label', 'location', 'status', 'site_id',
        ]);

        return response()->json(['data' => $rows]);
    }
}
