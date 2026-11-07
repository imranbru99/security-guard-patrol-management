<?php

declare(strict_types=1);

namespace App\Modules\Sos\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Sos\Models\SosEvent;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

final class GuardSosPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static string $view = 'Sos::filament.guard-sos';

    protected static ?string $navigationLabel = 'SOS';

    protected static ?string $title = 'SOS & panic';

    protected static ?string $slug = 'sos';

    protected static ?int $navigationSort = 1;

    public bool $silent = false;

    public bool $drillMode = false;

    public static function moduleId(): string
    {
        return 'sos';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public function triggerSos(): void
    {
        SosEvent::query()->create([
            'kind' => 'panic',
            'silent' => $this->silent,
            'drill_mode' => $this->drillMode,
            'status' => 'open',
            'triggered_at' => now(),
            'meta' => ['source' => 'guard_panel'],
        ]);

        Notification::make()
            ->title($this->drillMode ? 'Drill SOS recorded' : 'SOS triggered')
            ->danger()
            ->send();
    }
}
