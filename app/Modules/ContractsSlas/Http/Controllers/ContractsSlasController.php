<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Http\Controllers;

use App\Modules\ContractsSlas\Models\Contract;
use Illuminate\Http\JsonResponse;

final class ContractsSlasController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'contracts_slas',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $contracts = Contract::query()->orderBy('name')->get([
            'ulid', 'client_id', 'site_id', 'name', 'reference', 'status', 'starts_at', 'ends_at',
        ]);

        return response()->json(['data' => $contracts]);
    }
}
