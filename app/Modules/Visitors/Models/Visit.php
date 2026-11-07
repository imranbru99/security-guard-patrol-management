<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Visit extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'visitor_id',
        'site_id',
        'host_name',
        'host_email',
        'purpose',
        'status',
        'badge_code',
        'expected_at',
        'checked_in_at',
        'checked_out_at',
        'expected_out_at',
        'watchlist_hit',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'expected_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'expected_out_at' => 'datetime',
            'watchlist_hit' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Visit $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if ($m->getAttribute('badge_code') === null || $m->getAttribute('badge_code') === '') {
                $m->setAttribute('badge_code', strtoupper(Str::random(8)));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Visitor, $this> */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
