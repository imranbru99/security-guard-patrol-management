<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FormSubmission extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'form_template_id',
        'submitted_by',
        'payload',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (FormSubmission $submission): void {
            if (empty($submission->getAttribute('ulid'))) {
                $submission->setAttribute('ulid', (string) Str::ulid());
            }
            if ($submission->submitted_at === null) {
                $submission->submitted_at = now();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<FormTemplate, $this>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
