<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TrackingSession extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'guard_id',
        'shift_started_at',
        'shift_ends_at',
        'started_at',
        'ended_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'shift_started_at' => 'datetime',
            'shift_ends_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TrackingSession $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * Privacy: pings are only allowed while the session is active and now is inside the shift window.
     */
    public function allowsPingAt(?Carbon $at = null): bool
    {
        $at ??= now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->ended_at !== null && $at->greaterThan($this->ended_at)) {
            return false;
        }

        return $at->betweenIncluded($this->shift_started_at, $this->shift_ends_at);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    /** @return HasMany<LocationPing, $this> */
    public function pings(): HasMany
    {
        return $this->hasMany(LocationPing::class);
    }
}
