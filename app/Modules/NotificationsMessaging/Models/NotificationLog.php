<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class NotificationLog extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'template_id',
        'rule_id',
        'channel',
        'recipient',
        'payload',
        'status',
        'error',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NotificationLog $log): void {
            if (empty($log->getAttribute('ulid'))) {
                $log->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    /**
     * @return BelongsTo<NotificationTemplate, $this>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    /**
     * @return BelongsTo<NotificationRule, $this>
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(NotificationRule::class, 'rule_id');
    }
}
