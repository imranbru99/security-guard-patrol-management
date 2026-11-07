<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Watchlist extends TenantModel
{
    use SoftDeletes;

    protected $table = 'watchlists';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'full_name',
        'id_number',
        'reason',
        'severity',
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
        static::creating(function (Watchlist $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
