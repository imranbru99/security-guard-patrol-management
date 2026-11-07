<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'site_zone_id',
        'name',
        'code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post): void {
            if ($post->getAttribute('ulid') === null || $post->getAttribute('ulid') === '') {
                $post->setAttribute('ulid', (string) Str::ulid());
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
     * @return BelongsTo<SiteZone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(SiteZone::class, 'site_zone_id');
    }

    /**
     * @return HasMany<PostRequirement, $this>
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(PostRequirement::class);
    }
}
