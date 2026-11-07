<?php

declare(strict_types=1);

namespace App\Modules\ClientPortalShell\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class ClientPortalController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'client_portal',
            'status' => 'enabled',
        ]);
    }
}
