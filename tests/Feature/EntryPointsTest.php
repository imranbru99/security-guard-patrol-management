<?php

declare(strict_types=1);

it('serves the public home page', function (): void {
    $this->get('/')->assertOk();
});

it('serves the health endpoint', function (): void {
    $this->get('/up')->assertOk();
});

it('redirects unauthenticated users to filament panel logins', function (): void {
    $this->get('/app')->assertRedirect();
    $this->get('/guard')->assertRedirect();
    $this->get('/client')->assertRedirect();
    $this->get('/control')->assertRedirect();
});

it('exposes platform admin panel login at /admin', function (): void {
    $response = $this->get('/admin');
    expect(in_array($response->status(), [200, 302], true))->toBeTrue();
});

it('exposes api health', function (): void {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertJsonPath('ok', true);
});
