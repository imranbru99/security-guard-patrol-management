<?php

declare(strict_types=1);

use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use App\Modules\ClientsSites\Models\Client;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolMonitoring\Models\PatrolEvent;
use App\Modules\PatrolMonitoring\Services\MissedCheckpointEvaluator;
use App\Modules\PatrolRoutes\Models\PatrolRoute;
use App\Modules\PatrolRoutes\Models\RouteCheckpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->tenant = Tenant::query()->create([
        'name' => 'Eval Co',
        'slug' => 'eval-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);
    TenantContext::set($this->tenant);

    $client = Client::query()->create([
        'tenant_id' => $this->tenant->id,
        'name' => 'Client',
        'is_active' => true,
    ]);

    $site = Site::query()->create([
        'tenant_id' => $this->tenant->id,
        'client_id' => $client->id,
        'name' => 'Site',
        'slug' => 'site',
        'is_active' => true,
    ]);

    $guard = Guard::query()->create([
        'tenant_id' => $this->tenant->id,
        'employee_id' => 'G-100',
        'name' => 'Guard',
        'status' => 'active',
    ]);

    $checkpoint = Checkpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $site->id,
        'name' => 'CP1',
        'verification_type' => 'qr',
        'is_active' => true,
    ]);

    $route = PatrolRoute::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $site->id,
        'name' => 'Route',
        'is_ordered' => true,
        'grace_minutes' => 0,
        'gps_radius_meters' => 50,
        'is_active' => true,
    ]);

    RouteCheckpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'patrol_route_id' => $route->id,
        'checkpoint_id' => $checkpoint->id,
        'sequence' => 1,
        'expected_minutes_from_start' => 1,
    ]);

    $this->tour = PatrolTour::query()->create([
        'tenant_id' => $this->tenant->id,
        'patrol_route_id' => $route->id,
        'guard_id' => $guard->id,
        'status' => PatrolTour::STATUS_ACTIVE,
        'started_at' => now()->subMinutes(30),
    ]);

    $this->evaluator = app(MissedCheckpointEvaluator::class);
});

afterEach(function (): void {
    TenantContext::clear();
});

it('creates a missed checkpoint event and is idempotent on re-run', function (): void {
    $first = $this->evaluator->evaluate();
    expect($first)->toHaveCount(1);
    expect(PatrolEvent::query()->count())->toBe(1);

    $second = $this->evaluator->evaluate();
    expect($second)->toHaveCount(0);
    expect(PatrolEvent::query()->count())->toBe(1);

    $event = PatrolEvent::query()->first();
    expect($event?->type)->toBe(PatrolEvent::TYPE_MISSED_CHECKPOINT);
    expect($event?->idempotency_key)->toBe('missed:'.$this->tour->id.':'.$first->first()->checkpoint_id);
});
