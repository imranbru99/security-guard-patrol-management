<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\PatrolExecution\Models\PatrolTour;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class MyPatrolPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-play';

    protected static string $view = 'PatrolExecution::filament.my-patrol';

    protected static ?string $navigationLabel = 'My patrol';

    protected static ?string $title = 'My patrol';

    protected static ?string $slug = 'my-patrol';

    public static function moduleId(): string
    {
        return 'patrol_execution';
    }

    public static function getNavigationGroup(): string
    {
        return 'Patrols';
    }

    /**
     * @return Collection<int, PatrolTour>
     */
    public function getActiveToursProperty(): Collection
    {
        return PatrolTour::query()
            ->with(['patrolRoute', 'guardEmployee'])
            ->where('status', PatrolTour::STATUS_ACTIVE)
            ->orderByDesc('started_at')
            ->limit(20)
            ->get();
    }
}
