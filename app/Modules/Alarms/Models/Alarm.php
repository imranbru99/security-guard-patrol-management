<?php

declare(strict_types=1);

namespace App\Modules\Alarms\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\ControlRoom\Models\DispatchJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Alarm extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'dispatch_job_id',
        'external_id',
        'source',
        'alarm_type',
        'severity',
        'status',
        'outcome_code',
        'false_alarm',
        'received_at',
        'acknowledged_at',
        'responded_at',
        'closed_at',
        'sla_seconds',
        'response_seconds',
        'sla_breached',
        'payload',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'false_alarm' => 'boolean',
            'received_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'responded_at' => 'datetime',
            'closed_at' => 'datetime',
            'sla_seconds' => 'integer',
            'response_seconds' => 'integer',
            'sla_breached' => 'boolean',
            'payload' => 'array',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Alarm $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if ($m->getAttribute('received_at') === null) {
                $m->setAttribute('received_at', now());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return BelongsTo<DispatchJob, $this> */
    public function dispatchJob(): BelongsTo
    {
        return $this->belongsTo(DispatchJob::class);
    }
}
