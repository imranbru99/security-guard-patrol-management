<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Toggle — GuardOps</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 2rem; background: #0f172a; color: #e2e8f0; }
        .card { max-width: 40rem; padding: 1.5rem; border: 1px solid #334155; border-radius: 0.75rem; }
        h1 { margin-top: 0; }
        code { background: #1e293b; padding: 0.15rem 0.4rem; border-radius: 0.25rem; }
    </style>
</head>
<body>
<main class="card">
    <h1>Demo Toggle module</h1>
    <p>This page is only reachable when the <code>demo_toggle</code> module is enabled for the current tenant.</p>
    <p>Signed in as: <strong>{{ $user?->email ?? 'guest' }}</strong></p>
</main>
</body>
</html>
