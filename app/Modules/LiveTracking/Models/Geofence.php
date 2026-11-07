<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Models;

use App\Core\Support\Geo;
use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Geofence extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'name',
        'polygon',
        'center_latitude',
        'center_longitude',
        'radius_meters',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'polygon' => 'array',
            'center_latitude' => 'float',
            'center_longitude' => 'float',
            'radius_meters' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Geofence $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    public function contains(float $lat, float $lng): bool
    {
        if (is_array($this->polygon) && $this->polygon !== []) {
            return Geo::pointInPolygon($lat, $lng, $this->polygon);
        }

        if ($this->center_latitude !== null && $this->center_longitude !== null && $this->radius_meters !== null) {
            return Geo::withinRadius(
                (float) $this->center_latitude,
                (float) $this->center_longitude,
                $lat,
                $lng,
                (float) $this->radius_meters,
            );
        }

        return false;
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return HasMany<GeofenceEvent, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(GeofenceEvent::class);
    }
}
