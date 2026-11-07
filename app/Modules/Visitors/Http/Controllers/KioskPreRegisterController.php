<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Http\Controllers;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\Visitors\Models\Visit;
use App\Modules\Visitors\Models\Visitor;
use App\Modules\Visitors\Models\Watchlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class KioskPreRegisterController
{
    public function show(string $tenantSlug): JsonResponse
    {
        $tenant = $this->resolveTenant($tenantSlug);

        return response()->json([
            'module' => 'visitors',
            'mode' => 'kiosk',
            'tenant' => $tenant->slug,
            'status' => 'ready',
        ]);
    }

    public function store(Request $request, string $tenantSlug): JsonResponse
    {
        $tenant = $this->resolveTenant($tenantSlug);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'company' => ['nullable', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:128'],
            'host_name' => ['nullable', 'string', 'max:255'],
            'host_email' => ['nullable', 'email', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'site_id' => ['nullable', 'integer'],
        ]);

        $visitor = Visitor::query()->create([
            'tenant_id' => $tenant->id,
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'id_number' => $data['id_number'] ?? null,
        ]);

        $watchlistHit = Watchlist::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where(function ($q) use ($data): void {
                $q->where('full_name', $data['full_name']);
                if (! empty($data['id_number'])) {
                    $q->orWhere('id_number', $data['id_number']);
                }
            })
            ->exists();

        $visit = Visit::query()->create([
            'tenant_id' => $tenant->id,
            'visitor_id' => $visitor->id,
            'site_id' => $data['site_id'] ?? null,
            'host_name' => $data['host_name'] ?? null,
            'host_email' => $data['host_email'] ?? null,
            'purpose' => $data['purpose'] ?? null,
            'status' => 'pre_registered',
            'expected_at' => now(),
            'watchlist_hit' => $watchlistHit,
        ]);

        return response()->json([
            'data' => [
                'visit_ulid' => $visit->ulid,
                'badge_code' => $visit->badge_code,
                'watchlist_hit' => $watchlistHit,
            ],
        ], 201);
    }

    private function resolveTenant(string $tenantSlug): Tenant
    {
        $tenant = Tenant::query()->where('slug', $tenantSlug)->firstOrFail();
        TenantContext::set($tenant);

        if (! app(ModuleManager::class)->enabled($tenant, 'visitors')) {
            abort(404);
        }

        return $tenant;
    }
}
