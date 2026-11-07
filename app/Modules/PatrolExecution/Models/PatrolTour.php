<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\PatrolRoutes\Models\PatrolRoute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PatrolTour extends TenantModel
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'patrol_route_id',
        'guard_id',
        'status',
        'started_at',
        'completed_at',
        'due_by',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'due_by' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PatrolTour $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /** @return BelongsTo<PatrolRoute, $this> */
    public function patrolRoute(): BelongsTo
    {
        return $this->belongsTo(PatrolRoute::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    /** @return HasMany<PatrolScan, $this> */
    public function scans(): HasMany
    {
        return $this->hasMany(PatrolScan::class);
    }
}
