<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Http\Controllers;

use App\Modules\LeadsCrm\Models\Lead;
use Illuminate\Http\JsonResponse;

final class LeadsCrmController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'leads_crm',
            'status' => 'enabled',
        ]);
    }

    public function leads(): JsonResponse
    {
        $leads = Lead::query()->orderByDesc('id')->limit(100)->get([
            'ulid', 'name', 'email', 'company', 'source', 'status',
        ]);

        return response()->json(['data' => $leads]);
    }
}
