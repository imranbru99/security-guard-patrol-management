<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SiteZone extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'name',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (SiteZone $zone): void {
            if ($zone->getAttribute('ulid') === null || $zone->getAttribute('ulid') === '') {
                $zone->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Site, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
