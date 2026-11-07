<?php

declare(strict_types=1);

namespace App\Modules\Sos\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Sos\Models\SosEvent;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class SosControlRoomPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-rss';

    protected static string $view = 'Sos::filament.sos-control-room';

    protected static ?string $navigationLabel = 'SOS queue';

    protected static ?string $title = 'SOS control room';

    protected static ?string $slug = 'sos-queue';

    public static function moduleId(): string
    {
        return 'sos';
    }

    public static function getNavigationGroup(): string
    {
        return 'Control';
    }

    /**
     * @return Collection<int, SosEvent>
     */
    public function getOpenEventsProperty(): Collection
    {
        return SosEvent::query()
            ->with(['guardEmployee', 'site'])
            ->whereIn('status', ['open', 'acknowledged'])
            ->orderByDesc('triggered_at')
            ->limit(100)
            ->get();
    }
}
