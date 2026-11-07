<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Client extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'code',
        'email',
        'phone',
        'notes',
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
        static::creating(function (Client $client): void {
            if ($client->getAttribute('ulid') === null || $client->getAttribute('ulid') === '') {
                $client->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<ClientContact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    /**
     * @return HasMany<Site, $this>
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
