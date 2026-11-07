<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CheckpointTag extends TenantModel
{
    public const STATUSES = ['active', 'damaged', 'missing'];

    protected $fillable = [
        'ulid',
        'tenant_id',
        'checkpoint_id',
        'tag_value',
        'status',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (CheckpointTag $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Checkpoint, $this> */
    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
