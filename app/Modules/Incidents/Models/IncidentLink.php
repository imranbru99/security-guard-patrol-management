<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncidentLink extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'incident_id',
        'linked_incident_id',
        'relation',
        'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (IncidentLink $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Incident, $this> */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /** @return BelongsTo<Incident, $this> */
    public function linkedIncident(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'linked_incident_id');
    }
}
