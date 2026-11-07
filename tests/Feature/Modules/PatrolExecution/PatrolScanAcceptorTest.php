<?php

declare(strict_types=1);

use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use App\Modules\ClientsSites\Models\Client;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\PatrolExecution\Models\PatrolScan;
use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolExecution\Services\InvalidPatrolScanException;
use App\Modules\PatrolExecution\Services\PatrolScanAcceptor;
use App\Modules\PatrolRoutes\Models\PatrolRoute;
use App\Modules\PatrolRoutes\Models\RouteCheckpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->tenant = Tenant::query()->create([
        'name' => 'Scan Co',
        'slug' => 'scan-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);
    TenantContext::set($this->tenant);

    $client = Client::query()->create([
        'tenant_id' => $this->tenant->id,
        'name' => 'Acme',
        'is_active' => true,
    ]);

    $this->site = Site::query()->create([
        'tenant_id' => $this->tenant->id,
        'client_id' => $client->id,
        'name' => 'HQ',
        'slug' => 'hq',
        'latitude' => 23.7800,
        'longitude' => 90.4100,
        'is_active' => true,
    ]);

    $this->guard = Guard::query()->create([
        'tenant_id' => $this->tenant->id,
        'employee_id' => 'G-001',
        'name' => 'Guard One',
        'status' => 'active',
    ]);

    $this->checkpointA = Checkpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $this->site->id,
        'name' => 'Gate A',
        'code' => 'A1',
        'verification_type' => 'gps',
        'latitude' => 23.7800,
        'longitude' => 90.4100,
        'radius_meters' => 100,
        'is_active' => true,
    ]);

    $this->checkpointB = Checkpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $this->site->id,
        'name' => 'Gate B',
        'code' => 'B1',
        'verification_type' => 'gps',
        'latitude' => 23.7810,
        'longitude' => 90.4110,
        'radius_meters' => 100,
        'is_active' => true,
    ]);

    $this->offRoute = Checkpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $this->site->id,
        'name' => 'Off route',
        'code' => 'X1',
        'verification_type' => 'qr',
        'latitude' => 23.7800,
        'longitude' => 90.4100,
        'radius_meters' => 100,
        'is_active' => true,
    ]);

    $this->route = PatrolRoute::query()->create([
        'tenant_id' => $this->tenant->id,
        'site_id' => $this->site->id,
        'name' => 'Night route',
        'is_ordered' => true,
        'grace_minutes' => 5,
        'gps_radius_meters' => 100,
        'is_active' => true,
    ]);

    RouteCheckpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'patrol_route_id' => $this->route->id,
        'checkpoint_id' => $this->checkpointA->id,
        'sequence' => 1,
        'expected_minutes_from_start' => 5,
    ]);

    RouteCheckpoint::query()->create([
        'tenant_id' => $this->tenant->id,
        'patrol_route_id' => $this->route->id,
        'checkpoint_id' => $this->checkpointB->id,
        'sequence' => 2,
        'expected_minutes_from_start' => 15,
    ]);

    $this->tour = PatrolTour::query()->create([
        'tenant_id' => $this->tenant->id,
        'patrol_route_id' => $this->route->id,
        'guard_id' => $this->guard->id,
        'status' => PatrolTour::STATUS_ACTIVE,
        'started_at' => now()->subMinutes(2),
    ]);

    $this->acceptor = app(PatrolScanAcceptor::class);
});

afterEach(function (): void {
    TenantContext::clear();
});

it('does not create a second scan for a duplicate client_uuid', function (): void {
    $uuid = (string) Str::uuid();

    $first = $this->acceptor->accept($this->tour, [
        'client_uuid' => $uuid,
        'checkpoint_id' => $this->checkpointA->id,
        'latitude' => 23.7800,
        'longitude' => 90.4100,
        'mock_location' => false,
    ]);

    $second = $this->acceptor->accept($this->tour, [
        'client_uuid' => $uuid,
        'checkpoint_id' => $this->checkpointA->id,
        'latitude' => 23.7800,
        'longitude' => 90.4100,
        'mock_location' => true,
    ]);

    expect($second->id)->toBe($first->id);
    expect(PatrolScan::query()->where('client_uuid', $uuid)->count())->toBe(1);
    expect($second->mock_location)->toBeFalse();
});

it('rejects a checkpoint that is not on the tour route', function (): void {
    $this->acceptor->accept($this->tour, [
        'client_uuid' => (string) Str::uuid(),
        'checkpoint_id' => $this->offRoute->id,
        'latitude' => 23.7800,
        'longitude' => 90.4100,
    ]);
})->throws(InvalidPatrolScanException::class, 'Checkpoint is not on this tour route.');
