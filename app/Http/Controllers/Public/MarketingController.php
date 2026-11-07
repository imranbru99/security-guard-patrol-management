<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Core\Modules\ManifestRegistry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class MarketingController extends Controller
{
    public function home(ManifestRegistry $registry): View
    {
        return view('public.home', [
            'modules' => $registry->all(),
        ]);
    }

    public function features(ManifestRegistry $registry): View
    {
        $modules = collect($registry->all())
            ->reject(fn (array $m): bool => ($m['id'] ?? '') === 'demo_toggle')
            ->groupBy(fn (array $m): string => (string) ($m['group'] ?? 'Other'));

        return view('public.features.index', [
            'groups' => $modules,
        ]);
    }

    public function featureShow(string $slug, ManifestRegistry $registry): View
    {
        foreach ($registry->all() as $manifest) {
            $page = $manifest['public_page'] ?? null;
            if (is_array($page) && ($page['slug'] ?? null) === $slug) {
                return view('public.features.show', [
                    'manifest' => $manifest,
                    'page' => $page,
                ]);
            }
        }

        abort(404);
    }

    public function solutions(): View
    {
        return view('public.solutions.index', [
            'solutions' => config('public_site.solutions', []),
        ]);
    }

    public function solutionShow(string $slug): View
    {
        $solutions = config('public_site.solutions', []);
        if (! isset($solutions[$slug])) {
            abort(404);
        }

        return view('public.solutions.show', [
            'slug' => $slug,
            'solution' => $solutions[$slug],
        ]);
    }

    public function pricing(ManifestRegistry $registry): View
    {
        $modules = collect($registry->all())
            ->reject(fn (array $m): bool => ($m['id'] ?? '') === 'demo_toggle' || ($m['plan_tier'] ?? '') === 'core')
            ->values();

        return view('public.pricing', [
            'modules' => $modules,
        ]);
    }

    public function demo(): View
    {
        return view('public.demo', [
            'users' => config('public_site.demo_users', []),
        ]);
    }

    public function page(string $key): View
    {
        $pages = config('public_site.pages', []);
        if (! isset($pages[$key])) {
            abort(404);
        }

        return view('public.page', [
            'key' => $key,
            'page' => $pages[$key],
        ]);
    }

    public function verify(string $code): View
    {
        return view('public.verify', [
            'code' => $code,
            'valid' => strlen($code) >= 8,
        ]);
    }

    public function quoteStore(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191'],
            'company' => ['nullable', 'string', 'max:191'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        // Lead persistence is handled by M21 when a platform intake tenant is configured.
        // Always acknowledge so the public form works without secrets.
        return response()
            ->view('public.quote-thanks', ['data' => $data], 201);
    }
}
