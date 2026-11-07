<?php

declare(strict_types=1);

namespace App\Modules\ReportsDashboards\Filament\Widgets;

use App\Core\Concerns\HasModuleGate;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class KpiPlaceholderWidget extends StatsOverviewWidget
{
    use HasModuleGate;

    protected static ?int $sort = 10;

    public static function moduleId(): string
    {
        return 'reports';
    }

    public static function canView(): bool
    {
        return self::canAccess();
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Patrols completed', '—')
                ->description('KPI placeholder'),
            Stat::make('Open incidents', '—')
                ->description('KPI placeholder'),
            Stat::make('Attendance %', '—')
                ->description('KPI placeholder'),
        ];
    }
}
