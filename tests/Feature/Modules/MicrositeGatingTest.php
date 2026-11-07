<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\Microsite\Models\TenantPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Microsite Co',
        'slug' => 'microsite-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'microsite@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers microsite as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'microsite')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'microsite')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'microsite'))->toBeFalse();
});

it('gates the microsite HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('microsite.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'microsite');

    $this->actingAs($this->user)
        ->get(route('microsite.show'))
        ->assertOk()
        ->assertJsonPath('module', 'microsite');
});

it('serves public microsite by tenant slug when enabled', function (): void {
    $this->get(route('microsite.public.home', ['tenantSlug' => 'microsite-co']))
        ->assertNotFound();

    app(ModuleManager::class)->enable($this->tenant, 'microsite');

    TenantContext::set($this->tenant);
    TenantPage::query()->create([
        'tenant_id' => $this->tenant->id,
        'title' => 'Home',
        'slug' => 'home',
        'page_type' => 'home',
        'body' => 'Welcome',
        'is_published' => true,
    ]);
    TenantContext::clear();

    $this->get(route('microsite.public.home', ['tenantSlug' => 'microsite-co']))
        ->assertOk()
        ->assertJsonPath('module', 'microsite')
        ->assertJsonPath('tenant', 'microsite-co')
        ->assertJsonPath('page.title', 'Home');
});
