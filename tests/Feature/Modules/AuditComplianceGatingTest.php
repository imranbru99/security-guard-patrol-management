<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource;
use App\Modules\AuditCompliance\Services\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Audit Co',
        'slug' => 'audit-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'audit@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('is always enabled as a core module without a tenant_modules row', function (): void {
    expect(Module::query()->where('key', 'audit')->value('is_core'))->toBeTrue();
    expect(app(ModuleManager::class)->enabled($this->tenant, 'audit'))->toBeTrue();
});

it('allows Filament resource access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(AuditEventResource::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(AuditEventResource::canAccess())->toBeFalse();
});

it('serves gated audit route when core module is on and 404 when temporarily off', function (): void {
    $this->actingAs($this->user)
        ->get(route('audit.show'))
        ->assertOk();

    Module::query()->where('key', 'audit')->update(['is_core' => false]);
    app(ModuleManager::class)->bustCache($this->tenant);

    $this->actingAs($this->user)
        ->get(route('audit.show'))
        ->assertNotFound();
});

it('builds a verifiable SHA-256 hash chain', function (): void {
    TenantContext::set($this->tenant);
    $logger = app(AuditLogger::class);

    $logger->append($this->tenant, 'user.created', User::class, $this->user->id, null, ['email' => $this->user->email], $this->user->id, '127.0.0.1');
    $logger->append($this->tenant, 'user.updated', User::class, $this->user->id, ['name' => 'A'], ['name' => 'B'], $this->user->id, '127.0.0.1');
    $logger->append($this->tenant, 'settings.changed', null, null, ['x' => 1], ['x' => 2], $this->user->id);

    $result = $logger->verifyChain($this->tenant);

    expect($result['valid'])->toBeTrue()
        ->and($result['checked'])->toBe(3)
        ->and($result['broken_at'])->toBeNull();

    TenantContext::clear();
});
