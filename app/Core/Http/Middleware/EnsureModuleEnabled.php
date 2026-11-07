<?php

declare(strict_types=1);

namespace App\Core\Http\Middleware;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureModuleEnabled
{
    public function __construct(
        private readonly ModuleManager $modules,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $tenant = TenantContext::get();

        if ($tenant === null) {
            abort(403, 'Tenant required.');
        }

        if (! $this->modules->enabled($tenant, $moduleKey)) {
            abort(404);
        }

        return $next($request);
    }
}
