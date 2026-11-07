<?php

declare(strict_types=1);

namespace App\Modules\PatrolMonitoring\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use App\Modules\PatrolExecution\Models\PatrolTour;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PatrolEvent extends TenantModel
{
    public const TYPE_MISSED_CHECKPOINT = 'missed_checkpoint';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'patrol_tour_id',
        'checkpoint_id',
        'type',
        'idempotency_key',
        'occurred_at',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PatrolEvent $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<PatrolTour, $this> */
    public function patrolTour(): BelongsTo
    {
        return $this->belongsTo(PatrolTour::class);
    }

    /** @return BelongsTo<Checkpoint, $this> */
    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
