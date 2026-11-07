<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GeofenceEvent extends TenantModel
{
    public const TYPE_ENTER = 'enter';

    public const TYPE_EXIT = 'exit';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'geofence_id',
        'guard_id',
        'tracking_session_id',
        'type',
        'occurred_at',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GeofenceEvent $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Geofence, $this> */
    public function geofence(): BelongsTo
    {
        return $this->belongsTo(Geofence::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    /** @return BelongsTo<TrackingSession, $this> */
    public function trackingSession(): BelongsTo
    {
        return $this->belongsTo(TrackingSession::class);
    }
}
