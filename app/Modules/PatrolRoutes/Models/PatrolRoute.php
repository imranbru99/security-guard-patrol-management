<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PatrolRoute extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'name',
        'is_ordered',
        'randomised',
        'min_dwell_seconds',
        'max_dwell_seconds',
        'grace_minutes',
        'gps_radius_meters',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_ordered' => 'boolean',
            'randomised' => 'boolean',
            'min_dwell_seconds' => 'integer',
            'max_dwell_seconds' => 'integer',
            'grace_minutes' => 'integer',
            'gps_radius_meters' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PatrolRoute $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
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

    /** @return HasMany<RouteCheckpoint, $this> */
    public function routeCheckpoints(): HasMany
    {
        return $this->hasMany(RouteCheckpoint::class)->orderBy('sequence');
    }

    /** @return HasMany<PatrolSchedule, $this> */
    public function schedules(): HasMany
    {
        return $this->hasMany(PatrolSchedule::class);
    }
}
