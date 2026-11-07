<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'API Co',
        'slug' => 'api-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'api@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers public_api as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'public_api')->where('is_core', false)->exists())->toBeTrue();
    expect(app(ModuleManager::class)->enabled($this->tenant, 'public_api'))->toBeFalse();
});

it('gates the public_api HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('public-api.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'public_api');

    $this->actingAs($this->user)
        ->get(route('public-api.show'))
        ->assertOk()
        ->assertJsonPath('module', 'public_api');
});

it('gates the webhook ping API when module is off then on', function (): void {
    $manager = app(ModuleManager::class);
    $token = $this->user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson(route('api.v1.webhooks.ping'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'public_api');

    $this->withToken($token)
        ->postJson(route('api.v1.webhooks.ping'), ['secret' => 'abc'])
        ->assertOk()
        ->assertJsonPath('module', 'public_api')
        ->assertJsonPath('signature_header', 'X-GuardOps-Signature');
});
