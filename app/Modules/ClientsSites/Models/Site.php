<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Site extends TenantModel
{
    use HasUniversalSlug;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'client_id',
        'name',
        'slug',
        'address',
        'latitude',
        'longitude',
        'geofence_polygon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'geofence_polygon' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Site $site): void {
            if ($site->getAttribute('ulid') === null || $site->getAttribute('ulid') === '') {
                $site->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->uniqueScope(function ($query, $model): void {
                $query->where('tenant_id', $model->tenant_id);
            });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany<SiteZone, $this>
     */
    public function zones(): HasMany
    {
        return $this->hasMany(SiteZone::class);
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
