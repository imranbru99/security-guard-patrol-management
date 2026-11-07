<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PatrolSchedule extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'patrol_route_id',
        'name',
        'interval_minutes',
        'window_start',
        'window_end',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'interval_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PatrolSchedule $m): void {
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
}
