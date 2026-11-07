<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\Privacy\Jobs\ErasureJob;
use App\Modules\Privacy\Models\DsarRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Privacy Co',
        'slug' => 'privacy-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'privacy@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('keeps privacy enabled as a core module after sync', function (): void {
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'privacy')->where('is_core', true)->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'privacy'))->toBeTrue();
});

it('serves the gated privacy route when tenant is set', function (): void {
    $this->actingAs($this->user)
        ->get(route('privacy.show'))
        ->assertOk()
        ->assertJsonPath('module', 'privacy');
});

it('runs ErasureJob stub to complete a DSAR', function (): void {
    TenantContext::set($this->tenant);
    $dsar = DsarRequest::query()->create([
        'tenant_id' => $this->tenant->id,
        'request_type' => 'erasure',
        'requester_email' => 'person@example.com',
        'status' => 'received',
        'due_at' => now()->addDays(30),
    ]);
    TenantContext::clear();

    (new ErasureJob($dsar->id))->handle();

    expect($dsar->fresh()->status)->toBe('completed');
    expect($dsar->fresh()->meta['erasure_stub'] ?? false)->toBeTrue();
});
