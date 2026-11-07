<?php

declare(strict_types=1);

namespace App\Modules\Billing\Http\Controllers;

use App\Modules\Billing\Models\Invoice;
use Illuminate\Http\JsonResponse;

final class BillingController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'billing',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $invoices = Invoice::query()->orderByDesc('id')->get([
            'ulid', 'number', 'status', 'total_cents', 'currency', 'issued_on', 'due_on',
        ]);

        return response()->json(['data' => $invoices]);
    }
}
