<?php

declare(strict_types=1);

namespace App\Modules\Integrations\Http\Controllers;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Modules\Integrations\Models\IntegrationConnection;
use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

final class IntegrationsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'integrations',
            'status' => 'enabled',
            'connections' => IntegrationConnection::query()->count(),
        ]);
    }

    public function icsFeed(string $tenantSlug): Response
    {
        $tenant = Tenant::query()->where('slug', $tenantSlug)->where('is_active', true)->first();
        abort_if($tenant === null, 404);
        abort_unless(app(ModuleManager::class)->enabled($tenant, 'integrations'), 404);

        TenantContext::set($tenant);

        try {
            $lines = [
                'BEGIN:VCALENDAR',
                'VERSION:2.0',
                'PRODID:-//GuardOps//Integrations//EN',
                'CALSCALE:GREGORIAN',
            ];

            if (class_exists(Shift::class) && Schema::hasTable('shifts')) {
                $shifts = Shift::query()
                    ->where('tenant_id', $tenant->id)
                    ->where('starts_at', '>=', now()->subDays(7))
                    ->orderBy('starts_at')
                    ->limit(200)
                    ->get();

                foreach ($shifts as $shift) {
                    $uid = $shift->ulid.'@guardops';
                    $start = optional($shift->starts_at)?->utc()?->format('Ymd\THis\Z') ?? '';
                    $end = optional($shift->ends_at)?->utc()?->format('Ymd\THis\Z') ?? '';
                    $summary = $shift->label ?: 'Shift';
                    $lines[] = 'BEGIN:VEVENT';
                    $lines[] = 'UID:'.$uid;
                    $lines[] = 'DTSTART:'.$start;
                    $lines[] = 'DTEND:'.$end;
                    $lines[] = 'SUMMARY:'.$this->escapeIcs($summary);
                    $lines[] = 'END:VEVENT';
                }
            }

            $lines[] = 'END:VCALENDAR';

            return response(implode("\r\n", $lines)."\r\n", 200, [
                'Content-Type' => 'text/calendar; charset=utf-8',
            ]);
        } finally {
            TenantContext::clear();
        }
    }

    private function escapeIcs(string $value): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\\;', '\\,', '\\n'], $value);
    }
}
