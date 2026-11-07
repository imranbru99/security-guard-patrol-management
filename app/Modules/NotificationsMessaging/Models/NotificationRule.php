<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NotificationRule extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'template_id',
        'name',
        'event',
        'channel',
        'conditions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NotificationRule $rule): void {
            if (empty($rule->getAttribute('ulid'))) {
                $rule->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<NotificationTemplate, $this>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
}
