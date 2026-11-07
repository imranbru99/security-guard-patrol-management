<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\Analytics\Models\KpiSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class KpiCalculator
{
    /**
     * @return array<string, int|float>
     */
    public function compute(Tenant $tenant): array
    {
        $metrics = [
            'guards_count' => $this->countIf('guards', $tenant->id),
            'sites_count' => $this->countIf('sites', $tenant->id),
            'shifts_count' => $this->countIf('shifts', $tenant->id),
            'incidents_count' => $this->countIf('incidents', $tenant->id),
            'invoices_count' => $this->countIf('invoices', $tenant->id),
            'expenses_count' => $this->countIf('expenses', $tenant->id),
        ];

        $metrics['open_invoices'] = $this->countIf('invoices', $tenant->id, "status != 'paid'");

        return $metrics;
    }

    public function snapshot(Tenant $tenant, string $period = 'daily'): KpiSnapshot
    {
        $metrics = $this->compute($tenant);
        $on = now()->toDateString();

        return KpiSnapshot::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'period' => $period,
                'snapshot_on' => $on,
            ],
            [
                'metrics' => $metrics,
            ]
        );
    }

    private function countIf(string $table, int $tenantId, ?string $extra = null): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $q = DB::table($table)->where('tenant_id', $tenantId);
        if (Schema::hasColumn($table, 'deleted_at')) {
            $q->whereNull('deleted_at');
        }
        if ($extra !== null) {
            $q->whereRaw($extra);
        }

        return (int) $q->count();
    }
}
