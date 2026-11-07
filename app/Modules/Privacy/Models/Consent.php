<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Consent extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'user_id', 'subject_type', 'subject_id',
        'consent_type', 'granted', 'notice_version', 'captured_at', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'granted' => 'boolean',
            'captured_at' => 'datetime',
            'meta' => 'array',
            'subject_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Consent $m): void {
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
