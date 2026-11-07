<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Http\Controllers;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\Catalog\Models\CatalogItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CatalogController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'catalog',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $items = CatalogItem::query()->orderBy('name')->get([
            'ulid', 'name', 'slug', 'item_type', 'price_cents', 'currency', 'is_published',
        ]);

        return response()->json(['data' => $items]);
    }

    public function publicList(Request $request): JsonResponse
    {
        $slug = (string) $request->route('tenantSlug');
        $tenant = Tenant::query()->where('slug', $slug)->where('is_active', true)->first();

        if ($tenant === null) {
            abort(404);
        }

        if (! app(ModuleManager::class)->enabled($tenant, 'catalog')) {
            abort(404);
        }

        TenantContext::set($tenant);

        try {
            $items = CatalogItem::query()
                ->where('is_published', true)
                ->orderBy('name')
                ->get(['ulid', 'name', 'slug', 'summary', 'price_cents', 'currency', 'item_type']);

            return response()->json([
                'module' => 'catalog',
                'tenant' => $tenant->slug,
                'data' => $items,
            ]);
        } finally {
            TenantContext::clear();
        }
    }
}
