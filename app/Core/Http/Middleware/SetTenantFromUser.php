<?php

declare(strict_types=1);

namespace App\Core\Http\Middleware;

use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromUser
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && isset($user->tenant_id) && $user->tenant_id) {
            $tenant = Tenant::query()->find($user->tenant_id);
            if ($tenant !== null) {
                TenantContext::set($tenant);
            }
        }

        try {
            return $next($request);
        } finally {
            TenantContext::clear();
        }
    }
}
