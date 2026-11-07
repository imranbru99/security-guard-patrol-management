<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftHandover\Models\PostOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskPostOrderAck extends TenantModel
{
    use SoftDeletes;

    protected $table = 'task_post_order_acks';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'post_order_id',
        'task_item_id',
        'guard_id',
        'acknowledged_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'acknowledged_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TaskPostOrderAck $m): void {
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

    /** @return BelongsTo<TaskItem, $this> */
    public function taskItem(): BelongsTo
    {
        return $this->belongsTo(TaskItem::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }
}
