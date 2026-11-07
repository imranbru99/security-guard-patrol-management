<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NotificationTemplate extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'key',
        'name',
        'channel',
        'subject',
        'body',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NotificationTemplate $template): void {
            if (empty($template->getAttribute('ulid'))) {
                $template->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<NotificationRule, $this>
     */
    public function rules(): HasMany
    {
        return $this->hasMany(NotificationRule::class, 'template_id');
    }
}
