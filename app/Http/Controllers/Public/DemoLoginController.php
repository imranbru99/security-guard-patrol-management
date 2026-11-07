<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DemoLoginController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const PANEL_HOME = [
        'admin' => '/admin',
        'app' => '/app',
        'guard' => '/guard',
        'client' => '/client',
        'control' => '/control',
    ];

    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'panel' => ['required', 'string', 'in:admin,app,guard,client,control'],
        ]);

        /** @var list<array{role: string, email: string, panel: string}> $demoUsers */
        $demoUsers = config('public_site.demo_users', []);
        $allowed = [];
        foreach ($demoUsers as $row) {
            $allowed[] = $row['email'];
        }

        if (! in_array($data['email'], $allowed, true)) {
            return back()->withErrors(['email' => 'Unknown demo account.']);
        }

        $user = User::query()->where('email', $data['email'])->first();
        if ($user === null) {
            return redirect()
                ->route('public.demo')
                ->withErrors(['email' => 'Demo users are not seeded yet. Run php artisan demo:install.']);
        }

        Auth::login($user, true);

        $home = self::PANEL_HOME[$data['panel']] ?? '/app';

        return redirect($home);
    }
}
