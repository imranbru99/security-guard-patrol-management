<?php

declare(strict_types=1);

namespace App\Modules\AuditCompliance\Filament\Resources\AuditEventResource\Pages;

use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource;
use Filament\Resources\Pages\ListRecords;

final class ListAuditEvents extends ListRecords
{
    protected static string $resource = AuditEventResource::class;
}
