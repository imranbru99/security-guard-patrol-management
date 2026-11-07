<?php

declare(strict_types=1);

namespace App\Modules\AuditCompliance\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AuditEvent extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'actor_id',
        'action',
        'subject_type',
        'subject_id',
        'before',
        'after',
        'ip',
        'hash',
        'prev_hash',
    ];

    protected function casts(): array
    {
        return [
            'before' => 'array',
            'after' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (AuditEvent $event): void {
            if (empty($event->getAttribute('ulid'))) {
                $event->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
