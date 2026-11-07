<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LoginHistory extends TenantModel
{
    protected $table = 'login_histories';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'user_id',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'successful',
    ];

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'successful' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LoginHistory $history): void {
            if (empty($history->getAttribute('ulid'))) {
                $history->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
