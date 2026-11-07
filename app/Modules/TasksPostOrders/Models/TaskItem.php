<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Post;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftHandover\Models\PostOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskItem extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'post_id',
        'assigned_guard_id',
        'post_order_id',
        'title',
        'description',
        'checklist',
        'status',
        'priority',
        'is_recurring',
        'recurrence_rule',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'checklist' => 'array',
            'is_recurring' => 'boolean',
            'due_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TaskItem $m): void {
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

    /** @return BelongsTo<Guard, $this> */
    public function assignedGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'assigned_guard_id');
    }

    /** @return BelongsTo<PostOrder, $this> */
    public function postOrder(): BelongsTo
    {
        return $this->belongsTo(PostOrder::class);
    }

    /** @return HasMany<TaskCompletion, $this> */
    public function completions(): HasMany
    {
        return $this->hasMany(TaskCompletion::class);
    }

    /** @return HasMany<TaskPostOrderAck, $this> */
    public function postOrderAcks(): HasMany
    {
        return $this->hasMany(TaskPostOrderAck::class);
    }
}
