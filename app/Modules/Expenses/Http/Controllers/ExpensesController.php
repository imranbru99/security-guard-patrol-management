<?php

declare(strict_types=1);

namespace App\Modules\Expenses\Http\Controllers;

use App\Modules\Expenses\Models\Expense;
use Illuminate\Http\JsonResponse;

final class ExpensesController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'expenses',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Expense::query()->orderByDesc('id')->get(['ulid', 'title', 'amount_cents', 'currency', 'status']),
        ]);
    }
}
