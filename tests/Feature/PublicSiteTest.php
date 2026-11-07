<?php

declare(strict_types=1);

use App\Core\Modules\ManifestRegistry;

it('serves core marketing pages with 200', function (string $path): void {
    $this->get($path)->assertOk();
})->with([
    '/',
    '/features',
    '/pricing',
    '/demo',
    '/solutions',
    '/about',
    '/contact',
    '/security',
    '/ai',
    '/mobile',
    '/integrations',
    '/api-docs',
    '/compare',
    '/tour',
    '/changelog',
    '/roadmap',
    '/status',
    '/careers',
    '/help',
    '/blog',
    '/case-studies',
    '/privacy',
    '/terms',
    '/cookies',
    '/dpa',
    '/biometric-consent',
    '/acceptable-use',
    '/quote',
    '/robots.txt',
    '/llms.txt',
    '/sitemap.xml',
    '/rss.xml',
    '/verify/ABCDEFGH1234',
]);

it('serves solution pages from config', function (): void {
    foreach (array_keys(config('public_site.solutions')) as $slug) {
        $this->get('/solutions/'.$slug)->assertOk();
    }
});

it('serves a module feature page from the registry', function (): void {
    $registry = app(ManifestRegistry::class);
    $manifest = collect($registry->all())->first(function (array $m): bool {
        return is_array($m['public_page'] ?? null) && ! empty($m['public_page']['slug']);
    });

    expect($manifest)->not->toBeNull();
    $slug = $manifest['public_page']['slug'];
    $this->get('/features/'.$slug)->assertOk()->assertSee($manifest['public_page']['hero'] ?? '', false);
});

it('includes JSON-LD on the home page', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('application/ld+json', false)
        ->assertSee('SoftwareApplication', false);
});

it('accepts a public quote request', function (): void {
    $this->post('/quote', [
        'name' => 'Alex Prospect',
        'email' => 'alex@example.test',
        'company' => 'Demo Co',
        'message' => 'Interested in enterprise preset',
    ])->assertCreated();
});

it('keeps the README feature matrix in sync with manifests', function (): void {
    $this->artisan('docs:sync-readme')->assertSuccessful();
    $this->artisan('docs:sync-readme', ['--check' => true])->assertSuccessful();
});
