<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ControlRoom\Models\DispatchJob;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class AlertQueuePage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static string $view = 'ControlRoom::filament.alert-queue';

    protected static ?string $navigationLabel = 'Alert queue';

    protected static ?string $title = 'Alert queue';

    protected static ?string $slug = 'alert-queue';

    protected static ?int $navigationSort = 2;

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
    public function getQueuedAlertsProperty(): Collection
    {
        return DispatchJob::query()
            ->with(['site', 'assignedGuardEmployee'])
            ->where('status', 'queued')
            ->orderByDesc('id')
            ->limit(100)
            ->get();
    }
}
