<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WebhookEndpoint extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'url',
        'secret',
        'events',
        'active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'active' => 'boolean',
            'secret' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (WebhookEndpoint $endpoint): void {
            if (empty($endpoint->getAttribute('ulid'))) {
                $endpoint->setAttribute('ulid', (string) Str::ulid());
            }
            if (empty($endpoint->secret)) {
                $endpoint->secret = Str::random(40);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<WebhookDeliveryLog, $this>
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDeliveryLog::class);
    }
}
