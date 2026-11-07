<?php

declare(strict_types=1);

namespace App\Modules\Localisation\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Support\Str;

class TranslationOverride extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'locale',
        'group',
        'key',
        'value',
    ];

    protected static function booted(): void
    {
        static::creating(function (TranslationOverride $override): void {
            if (empty($override->getAttribute('ulid'))) {
                $override->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
