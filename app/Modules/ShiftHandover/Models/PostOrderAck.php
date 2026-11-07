<?php

declare(strict_types=1);

namespace App\Modules\ShiftHandover\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PostOrderAck extends TenantModel
{
    use SoftDeletes;

    protected $table = 'post_order_acks';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'post_order_id',
        'guard_id',
        'acknowledged_at',
    ];

    protected function casts(): array
    {
        return [
            'acknowledged_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PostOrderAck $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<PostOrder, $this> */
    public function postOrder(): BelongsTo
    {
        return $this->belongsTo(PostOrder::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }
}
