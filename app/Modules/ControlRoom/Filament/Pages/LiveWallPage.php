<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ControlRoom\Models\DispatchJob;
use App\Modules\Sos\Models\SosEvent;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class LiveWallPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-tv';

    protected static string $view = 'ControlRoom::filament.live-wall';

    protected static ?string $navigationLabel = 'Live wall';

    protected static ?string $title = 'Control room live wall';

    protected static ?string $slug = 'live-wall';

    protected static ?int $navigationSort = 1;

    public static function moduleId(): string
    {
        return 'control_room';
    }

    public static function getNavigationGroup(): string
    {
        return 'Control';
    }

    /**
     * @return Collection<int, DispatchJob>
     */
    public function getActiveJobsProperty(): Collection
    {
        return DispatchJob::query()
            ->with(['site', 'assignedGuardEmployee'])
            ->whereIn('status', ['queued', 'acknowledged', 'dispatched'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();
    }

    /**
     * @return Collection<int, SosEvent>
     */
    public function getOpenSosProperty(): Collection
    {
        if (! class_exists(SosEvent::class)) {
            return collect();
        }

        return SosEvent::query()
            ->with('guardEmployee')
            ->where('status', 'open')
            ->orderByDesc('triggered_at')
            ->limit(20)
            ->get();
    }
}
