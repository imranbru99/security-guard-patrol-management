<?php

declare(strict_types=1);

use App\Core\Tenancy\Tenant;
use App\Modules\Incidents\Models\Incident;
use App\Modules\Incidents\Models\IncidentTimeline;
use App\Modules\Incidents\Services\IncidentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->tenant = Tenant::query()->create([
        'name' => 'Workflow Co',
        'slug' => 'workflow-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->incident = Incident::query()->create([
        'tenant_id' => $this->tenant->id,
        'title' => 'Gate left open',
        'severity' => 'high',
        'status' => 'reported',
    ]);

    $this->workflow = new IncidentWorkflow;
});

it('transitions reported → triaged → in_progress → resolved → closed', function (): void {
    $this->workflow->transition($this->incident, 'triaged', 'Accepted by ops');
    expect($this->incident->fresh()->status)->toBe('triaged');
    expect($this->incident->fresh()->triaged_at)->not->toBeNull();

    $this->workflow->transition($this->incident->fresh(), 'in_progress');
    $this->workflow->transition($this->incident->fresh(), 'resolved');
    expect($this->incident->fresh()->resolved_at)->not->toBeNull();

    $this->workflow->transition($this->incident->fresh(), 'closed');
    expect($this->incident->fresh()->status)->toBe('closed');
    expect($this->incident->fresh()->closed_at)->not->toBeNull();

    expect(IncidentTimeline::query()->where('incident_id', $this->incident->id)->count())->toBe(4);
});

it('allows reopen from closed and clears closed_at', function (): void {
    $this->workflow->transition($this->incident, 'closed');
    $this->workflow->transition($this->incident->fresh(), 'reopened', 'New evidence');

    $fresh = $this->incident->fresh();
    expect($fresh->status)->toBe('reopened');
    expect($fresh->closed_at)->toBeNull();
});

it('rejects illegal transitions', function (): void {
    expect(fn () => $this->workflow->transition($this->incident, 'resolved'))
        ->toThrow(InvalidArgumentException::class);
});

it('lists allowed transitions for the current status', function (): void {
    expect($this->workflow->allowedTransitions($this->incident))->toBe(['triaged', 'closed']);
});
