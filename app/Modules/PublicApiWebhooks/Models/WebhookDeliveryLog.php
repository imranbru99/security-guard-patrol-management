<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WebhookDeliveryLog extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'webhook_endpoint_id',
        'event',
        'payload',
        'response_status',
        'response_body',
        'success',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'success' => 'boolean',
            'attempted_at' => 'datetime',
            'response_status' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (WebhookDeliveryLog $log): void {
            if (empty($log->getAttribute('ulid'))) {
                $log->setAttribute('ulid', (string) Str::ulid());
            }
            if ($log->attempted_at === null) {
                $log->attempted_at = now();
            }
        });
    }

    /**
     * @return BelongsTo<WebhookEndpoint, $this>
     */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }
}
