<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Roster extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'site_id',
        'rotation_id',
        'starts_on',
        'ends_on',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Roster $m): void {
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

    /** @return BelongsTo<Rotation, $this> */
    public function rotation(): BelongsTo
    {
        return $this->belongsTo(Rotation::class);
    }

    /** @return HasMany<Shift, $this> */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    /** @return HasMany<CoverageRequirement, $this> */
    public function coverageRequirements(): HasMany
    {
        return $this->hasMany(CoverageRequirement::class);
    }
}
