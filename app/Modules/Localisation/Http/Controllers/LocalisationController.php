<?php

declare(strict_types=1);

namespace App\Modules\Localisation\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class LocalisationController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'localisation',
            'status' => 'enabled',
            'locales' => config('guardops.locales', []),
            'note' => 'AI translation sync comes later.',
        ]);
    }
}
