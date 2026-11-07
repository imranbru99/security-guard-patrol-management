<?php

declare(strict_types=1);

namespace App\Modules\Emergency\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EmergencyPlan extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'title',
        'body',
        'muster_points',
        'is_active',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'muster_points' => 'array',
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (EmergencyPlan $m): void {
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

    /** @return HasMany<Drill, $this> */
    public function drills(): HasMany
    {
        return $this->hasMany(Drill::class);
    }
}
