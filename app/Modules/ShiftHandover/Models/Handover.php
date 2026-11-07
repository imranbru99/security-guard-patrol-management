<?php

declare(strict_types=1);

namespace App\Modules\ShiftHandover\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Post;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Handover extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'shift_id',
        'from_guard_id',
        'to_guard_id',
        'site_id',
        'post_id',
        'notes',
        'open_items',
        'status',
        'handed_over_at',
        'acknowledged_at',
    ];

    protected function casts(): array
    {
        return [
            'open_items' => 'array',
            'handed_over_at' => 'datetime',
            'acknowledged_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Handover $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Shift, $this> */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function fromGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'from_guard_id');
    }

    /** @return BelongsTo<Guard, $this> */
    public function toGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'to_guard_id');
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
}
