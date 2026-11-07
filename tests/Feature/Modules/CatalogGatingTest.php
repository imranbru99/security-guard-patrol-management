<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Catalog\Services\CatalogCopyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Catalog Co',
        'slug' => 'catalog-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'catalog@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers catalog as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'catalog')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'catalog')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'catalog'))->toBeFalse();
});

it('gates the catalog HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('catalog.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'catalog');

    $this->actingAs($this->user)
        ->get(route('catalog.show'))
        ->assertOk()
        ->assertJsonPath('module', 'catalog');
});

it('serves public catalog list when enabled', function (): void {
    $this->getJson(route('catalog.public.items', ['tenantSlug' => 'catalog-co']))
        ->assertNotFound();

    app(ModuleManager::class)->enable($this->tenant, 'catalog');

    $this->getJson(route('catalog.public.items', ['tenantSlug' => 'catalog-co']))
        ->assertOk()
        ->assertJsonPath('module', 'catalog');
});

it('returns canned listing from CatalogCopyService', function (): void {
    $listing = app(CatalogCopyService::class)->generateListing('night patrol');

    expect($listing['title'])->toContain('night patrol');
    expect($listing['source'])->toBeIn(['aihub_fake', 'canned']);
});
