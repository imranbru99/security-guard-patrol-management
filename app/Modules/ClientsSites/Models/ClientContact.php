<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClientContact extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'client_id',
        'name',
        'email',
        'phone',
        'title',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ClientContact $contact): void {
            if ($contact->getAttribute('ulid') === null || $contact->getAttribute('ulid') === '') {
                $contact->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
