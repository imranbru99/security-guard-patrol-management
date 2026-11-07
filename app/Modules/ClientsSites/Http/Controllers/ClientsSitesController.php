<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Http\Controllers;

use App\Modules\ClientsSites\Models\Client;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Http\JsonResponse;

final class ClientsSitesController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'clients_sites',
            'status' => 'enabled',
        ]);
    }

    public function clients(): JsonResponse
    {
        $clients = Client::query()->orderBy('name')->get(['ulid', 'name', 'code', 'email', 'is_active']);

        return response()->json(['data' => $clients]);
    }

    public function sites(): JsonResponse
    {
        $sites = Site::query()->orderBy('name')->get([
            'ulid', 'client_id', 'name', 'slug', 'address', 'latitude', 'longitude', 'is_active',
        ]);

        return response()->json(['data' => $sites]);
    }
}
