<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TaskCompletion extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'task_item_id',
        'completed_by_guard_id',
        'completed_at',
        'notes',
        'proof_path',
        'checklist_answers',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'checklist_answers' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TaskCompletion $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<TaskItem, $this> */
    public function taskItem(): BelongsTo
    {
        return $this->belongsTo(TaskItem::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function completedByGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'completed_by_guard_id');
    }
}
