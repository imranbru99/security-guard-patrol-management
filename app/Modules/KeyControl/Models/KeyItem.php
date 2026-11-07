<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Post;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KeyItem extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'post_id',
        'code',
        'label',
        'location',
        'status',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (KeyItem $m): void {
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

    /** @return BelongsTo<Post, $this> */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /** @return HasMany<KeyLog, $this> */
    public function logs(): HasMany
    {
        return $this->hasMany(KeyLog::class);
    }
}
