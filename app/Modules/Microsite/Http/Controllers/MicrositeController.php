<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Http\Controllers;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\Microsite\Models\TenantPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MicrositeController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'microsite',
            'status' => 'enabled',
        ]);
    }

    public function publicSite(Request $request): JsonResponse
    {
        $slug = (string) $request->route('tenantSlug');
        $pageSlug = (string) ($request->route('pageSlug') ?: 'home');

        $tenant = Tenant::query()->where('slug', $slug)->where('is_active', true)->first();
        abort_if($tenant === null, 404);
        abort_unless(app(ModuleManager::class)->enabled($tenant, 'microsite'), 404);

        TenantContext::set($tenant);

        try {
            $page = TenantPage::query()
                ->where('is_published', true)
                ->where(function ($q) use ($pageSlug): void {
                    $q->where('slug', $pageSlug)->orWhere('page_type', $pageSlug);
                })
                ->orderBy('sort_order')
                ->first();

            $pages = TenantPage::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->get(['ulid', 'title', 'slug', 'page_type']);

            return response()->json([
                'module' => 'microsite',
                'tenant' => $tenant->slug,
                'page' => $page ? [
                    'ulid' => $page->ulid,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'page_type' => $page->page_type,
                    'body' => $page->body,
                    'seo' => $page->seo,
                ] : null,
                'nav' => $pages,
            ]);
        } finally {
            TenantContext::clear();
        }
    }
}
