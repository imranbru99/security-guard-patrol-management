<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Post;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Incident extends TenantModel
{
    use SoftDeletes;

    public const STATUSES = [
        'reported',
        'triaged',
        'in_progress',
        'resolved',
        'closed',
        'reopened',
    ];

    protected $fillable = [
        'ulid',
        'tenant_id',
        'incident_type_id',
        'site_id',
        'post_id',
        'shift_id',
        'reported_by_guard_id',
        'assigned_to_guard_id',
        'title',
        'summary',
        'severity',
        'status',
        'occurred_at',
        'reported_at',
        'triaged_at',
        'resolved_at',
        'closed_at',
        'sla_due_at',
        'police_report_no',
        'injuries',
        'tags',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'reported_at' => 'datetime',
            'triaged_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'sla_due_at' => 'datetime',
            'injuries' => 'boolean',
            'tags' => 'array',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Incident $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if ($m->getAttribute('reported_at') === null) {
                $m->setAttribute('reported_at', now());
            }
            if ($m->getAttribute('status') === null || $m->getAttribute('status') === '') {
                $m->setAttribute('status', 'reported');
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<IncidentType, $this> */
    public function incidentType(): BelongsTo
    {
        return $this->belongsTo(IncidentType::class);
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return BelongsTo<Post, $this> */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /** @return BelongsTo<Shift, $this> */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function reportedByGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'reported_by_guard_id');
    }

    /** @return BelongsTo<Guard, $this> */
    public function assignedToGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'assigned_to_guard_id');
    }

    /** @return HasMany<IncidentPerson, $this> */
    public function people(): HasMany
    {
        return $this->hasMany(IncidentPerson::class);
    }

    /** @return HasMany<IncidentVehicle, $this> */
    public function vehicles(): HasMany
    {
        return $this->hasMany(IncidentVehicle::class);
    }

    /** @return HasMany<IncidentMedia, $this> */
    public function media(): HasMany
    {
        return $this->hasMany(IncidentMedia::class);
    }

    /** @return HasMany<IncidentTimeline, $this> */
    public function timelines(): HasMany
    {
        return $this->hasMany(IncidentTimeline::class);
    }

    /** @return HasMany<IncidentLink, $this> */
    public function links(): HasMany
    {
        return $this->hasMany(IncidentLink::class);
    }

    /** @return HasMany<CorrectiveAction, $this> */
    public function correctiveActions(): HasMany
    {
        return $this->hasMany(CorrectiveAction::class);
    }
}
