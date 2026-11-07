<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Http\Controllers;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\LeadsCrm\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class QuoteRequestController
{
    public function store(Request $request, ModuleManager $modules): JsonResponse
    {
        $validated = $request->validate([
            'tenant' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        $tenant = Tenant::query()
            ->where('slug', $validated['tenant'])
            ->where('is_active', true)
            ->first();

        if ($tenant === null) {
            abort(404);
        }

        if (! $modules->enabled($tenant, 'leads_crm')) {
            abort(404);
        }

        TenantContext::set($tenant);

        try {
            $lead = Lead::query()->create([
                'tenant_id' => $tenant->id,
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'message' => $validated['message'] ?? null,
                'source' => 'quote_request',
                'status' => 'new',
            ]);
        } finally {
            TenantContext::clear();
        }

        return response()->json([
            'module' => 'leads_crm',
            'status' => 'received',
            'lead_ulid' => $lead->ulid,
        ], 201);
    }
}
