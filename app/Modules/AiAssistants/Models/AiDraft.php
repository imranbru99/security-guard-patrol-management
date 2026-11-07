<?php

declare(strict_types=1);

namespace App\Modules\AiAssistants\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AiDraft extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'feature',
        'subject_type',
        'subject_id',
        'status',
        'input',
        'output',
        'provider',
        'model',
        'job_tag',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'input' => 'array',
            'output' => 'array',
            'subject_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (AiDraft $m): void {
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
