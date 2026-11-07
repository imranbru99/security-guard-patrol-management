<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RouteCheckpoint extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'patrol_route_id',
        'checkpoint_id',
        'sequence',
        'expected_minutes_from_start',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'expected_minutes_from_start' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RouteCheckpoint $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<PatrolRoute, $this> */
    public function patrolRoute(): BelongsTo
    {
        return $this->belongsTo(PatrolRoute::class);
    }

    /** @return BelongsTo<Checkpoint, $this> */
    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
