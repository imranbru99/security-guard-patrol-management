<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RetentionPolicy extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'data_class', 'retain_days', 'action', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'retain_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RetentionPolicy $m): void {
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
