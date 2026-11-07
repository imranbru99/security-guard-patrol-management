<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Tenancy\TenantContext;
use App\Modules\Analytics\Services\KpiCalculator;
use Filament\Pages\Page;

final class AnalyticsDashboard extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'Analytics::filament.analytics-dashboard';

    protected static ?string $navigationLabel = 'Analytics';

    protected static ?string $title = 'Analytics & KPIs';

    protected static ?string $slug = 'analytics';

    public static function moduleId(): string
    {
        return 'analytics';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    /**
     * @return array<string, int|float>
     */
    public function getKpisProperty(): array
    {
        $tenant = TenantContext::get();
        if ($tenant === null) {
            return [];
        }

        return app(KpiCalculator::class)->compute($tenant);
    }
}
