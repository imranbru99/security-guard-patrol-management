<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DsarRequest extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'request_type', 'requester_email', 'status', 'due_at', 'completed_at', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DsarRequest $m): void {
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
