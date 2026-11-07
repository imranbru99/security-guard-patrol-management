<?php

declare(strict_types=1);

namespace App\Modules\SmartScheduling\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\SmartScheduling\Models\ForecastRun;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class SmartSchedulingPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'SmartScheduling::filament.smart-scheduling';

    protected static ?string $navigationLabel = 'Smart scheduling';

    protected static ?string $title = 'Smart scheduling';

    protected static ?string $slug = 'smart-scheduling';

    public static function moduleId(): string
    {
        return 'smart_scheduling';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    /** @return Collection<int, ForecastRun> */
    public function getRecentRunsProperty(): Collection
    {
        return ForecastRun::query()->orderByDesc('ran_at')->limit(25)->get();
    }
}
