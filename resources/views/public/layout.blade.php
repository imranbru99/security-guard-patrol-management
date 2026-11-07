<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'GuardOps') — GuardOps</title>
    <meta name="description" content="@yield('description', 'GuardOps helps security companies plan shifts, verify patrols, capture incidents, and report to clients.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    @stack('hreflang')
    <meta property="og:title" content="@yield('title', 'GuardOps')">
    <meta property="og:description" content="@yield('description', 'Operations software for security companies.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #e8eef2;
            --surface: #f4f8fa;
            --ink: #0f1c24;
            --muted: #3d5360;
            --brand: #0a3d4a;
            --cta: #145c6b;
            --cta-ink: #f4fafb;
            --line: #c5d4de;
            --hero-glow: radial-gradient(ellipse 80% 60% at 70% 10%, #9eb8c6 0%, transparent 55%),
                         linear-gradient(160deg, #d5e2ea 0%, #e8eef2 45%, #b7c9d4 100%);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0b1419;
                --surface: #101c24;
                --ink: #e8f0f3;
                --muted: #9bb0bb;
                --brand: #e8f0f3;
                --cta: #3aa8b8;
                --cta-ink: #061015;
                --line: #1e3340;
                --hero-glow: radial-gradient(ellipse 70% 50% at 75% 8%, #1a3a45 0%, transparent 50%),
                             linear-gradient(165deg, #0b1419 0%, #101c24 50%, #0e2a33 100%);
            }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "IBM Plex Sans", system-ui, sans-serif;
            color: var(--ink);
            background: var(--bg);
            line-height: 1.55;
            min-height: 100vh;
        }
        a { color: var(--cta); }
        a:focus-visible, button:focus-visible {
            outline: 2px solid var(--cta);
            outline-offset: 3px;
        }
        .skip {
            position: absolute; left: -999px; top: 0;
            background: var(--cta); color: var(--cta-ink); padding: .5rem 1rem;
        }
        .skip:focus { left: 0; z-index: 50; }
        .site-header {
            border-bottom: 1px solid var(--line);
            background: color-mix(in srgb, var(--surface) 88%, transparent);
            backdrop-filter: blur(8px);
            position: sticky; top: 0; z-index: 20;
        }
        .nav {
            max-width: 72rem; margin: 0 auto; padding: .85rem 1.25rem;
            display: flex; flex-wrap: wrap; gap: .75rem 1.25rem; align-items: center;
        }
        .nav-brand {
            font-family: "Source Serif 4", Georgia, serif;
            font-weight: 700; font-size: 1.25rem; color: var(--brand); text-decoration: none;
            margin-right: auto;
        }
        .nav a:not(.nav-brand) {
            text-decoration: none; color: var(--muted); font-size: .92rem; font-weight: 500;
        }
        .nav a:not(.nav-brand):hover { color: var(--ink); }
        .wrap { max-width: 72rem; margin: 0 auto; padding: 2rem 1.25rem 4rem; }
        .hero {
            background: var(--hero-glow);
            border-bottom: 1px solid var(--line);
            padding: clamp(2.5rem, 8vw, 5rem) 1.25rem;
        }
        .hero-inner { max-width: 72rem; margin: 0 auto; }
        .brand-hero {
            font-family: "Source Serif 4", Georgia, serif;
            font-size: clamp(2.5rem, 7vw, 4rem);
            font-weight: 700; color: var(--brand); letter-spacing: -0.02em; line-height: 1.05;
        }
        h1, h2, h3 {
            font-family: "Source Serif 4", Georgia, serif;
            font-weight: 600; line-height: 1.25; color: var(--brand);
        }
        h1 { font-size: clamp(1.5rem, 3vw, 2rem); margin-top: 1rem; max-width: 28ch; }
        h2 { font-size: 1.35rem; margin: 2rem 0 .75rem; }
        h3 { font-size: 1.1rem; margin: 1.25rem 0 .4rem; }
        .lede { margin-top: .85rem; color: var(--muted); max-width: 40rem; font-size: 1.05rem; }
        .cta {
            display: inline-flex; align-items: center; gap: .4rem;
            margin-top: 1.5rem; padding: .85rem 1.35rem;
            background: var(--cta); color: var(--cta-ink); text-decoration: none;
            font-weight: 600; border: 0; border-radius: 2px; cursor: pointer; font-size: 1rem;
        }
        .cta.secondary { background: transparent; color: var(--cta); border: 1px solid var(--cta); margin-left: .5rem; }
        .grid {
            display: grid; gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(16rem, 1fr));
            margin-top: 1.25rem;
        }
        .tile {
            border: 1px solid var(--line); background: var(--surface);
            padding: 1rem 1.1rem; text-decoration: none; color: inherit;
            transition: border-color 160ms ease;
        }
        .tile:hover { border-color: var(--cta); }
        .tile .meta { color: var(--muted); font-size: .85rem; margin-top: .35rem; }
        .prose p { margin: .75rem 0; color: var(--muted); max-width: 48rem; }
        .site-footer {
            border-top: 1px solid var(--line); padding: 2rem 1.25rem;
            color: var(--muted); font-size: .9rem;
        }
        .footer-inner {
            max-width: 72rem; margin: 0 auto;
            display: grid; gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr));
        }
        .site-footer a { color: var(--muted); text-decoration: none; display: block; margin: .25rem 0; }
        .site-footer a:hover { color: var(--ink); }
        .cookie {
            position: fixed; bottom: 1rem; left: 1rem; right: 1rem; max-width: 28rem;
            background: var(--surface); border: 1px solid var(--line); padding: 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 40;
        }
        .cookie[hidden] { display: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: .92rem; }
        th, td { text-align: left; padding: .55rem .4rem; border-bottom: 1px solid var(--line); }
        .badge {
            display: inline-block; font-size: .75rem; padding: .15rem .45rem;
            border: 1px solid var(--line); color: var(--muted);
        }
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; }
        }
    </style>
    @stack('head')
    @include('public.partials.json-ld')
</head>
<body>
    <a class="skip" href="#main">Skip to content</a>
    @include('public.partials.nav')
    <main id="main">
        @yield('content')
    </main>
    @include('public.partials.footer')
    <div class="cookie" id="cookie-banner" role="dialog" aria-label="Cookie consent" hidden>
        <p>We use essential cookies for sign-in. Optional analytics stay off until you accept.</p>
        <p style="margin-top:.75rem">
            <button type="button" class="cta" id="cookie-accept">Accept analytics</button>
            <button type="button" class="cta secondary" id="cookie-reject">Essential only</button>
        </p>
    </div>
    <script>
        (function () {
            var key = 'guardops_cookie_consent';
            var el = document.getElementById('cookie-banner');
            if (!el) return;
            if (!localStorage.getItem(key)) el.hidden = false;
            document.getElementById('cookie-accept')?.addEventListener('click', function () {
                localStorage.setItem(key, 'accepted'); el.hidden = true;
            });
            document.getElementById('cookie-reject')?.addEventListener('click', function () {
                localStorage.setItem(key, 'essential'); el.hidden = true;
            });
        })();
    </script>
</body>
</html>
