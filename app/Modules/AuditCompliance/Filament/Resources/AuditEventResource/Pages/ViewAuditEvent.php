<?php

declare(strict_types=1);

namespace App\Modules\AuditCompliance\Filament\Resources\AuditEventResource\Pages;

use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewAuditEvent extends ViewRecord
{
    protected static string $resource = AuditEventResource::class;
}
