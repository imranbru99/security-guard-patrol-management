<?php

declare(strict_types=1);

namespace App\Modules\LiveTracking\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\LiveTracking\Models\LocationPing;
use App\Modules\LiveTracking\Models\TrackingSession;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class LiveTrackingPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static string $view = 'LiveTracking::filament.live-tracking';

    protected static ?string $navigationLabel = 'Live tracking';

    protected static ?string $title = 'Live tracking';

    protected static ?string $slug = 'live-tracking';

    public static function moduleId(): string
    {
        return 'live_tracking';
    }

    public static function getNavigationGroup(): string
    {
        return 'Patrols';
    }

    /**
     * @return Collection<int, TrackingSession>
     */
    public function getActiveSessionsProperty(): Collection
    {
        return TrackingSession::query()
            ->with('guardEmployee')
            ->where('is_active', true)
            ->orderByDesc('started_at')
            ->limit(50)
            ->get();
    }

    /**
     * @return Collection<int, LocationPing>
     */
    public function getRecentPingsProperty(): Collection
    {
        return LocationPing::query()
            ->with('guardEmployee')
            ->orderByDesc('recorded_at')
            ->limit(50)
            ->get();
    }
}
