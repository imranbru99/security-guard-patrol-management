<?php

declare(strict_types=1);

namespace App\Modules\Localisation\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stub: apply authenticated user's locale (or tenant default) to the app.
 * Wire into the panel/web stack once user preference UI is complete.
 */
final class SetUserLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $locale = config('app.locale', 'en');

        if ($user instanceof User) {
            $tenantLocale = $user->tenant !== null ? $user->tenant->locale : null;
            $locale = $user->locale ?? $tenantLocale ?? $locale;
        }

        if (is_string($locale) && $locale !== '') {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
