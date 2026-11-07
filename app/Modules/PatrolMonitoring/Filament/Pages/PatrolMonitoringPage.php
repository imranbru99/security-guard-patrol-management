<?php

declare(strict_types=1);

namespace App\Modules\PatrolMonitoring\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\PatrolExecution\Models\PatrolTour;
use App\Modules\PatrolMonitoring\Models\PatrolEvent;
use App\Modules\PatrolMonitoring\Services\MissedCheckpointEvaluator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class PatrolMonitoringPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static string $view = 'PatrolMonitoring::filament.monitoring';

    protected static ?string $navigationLabel = 'Patrol monitoring';

    protected static ?string $title = 'Patrol monitoring';

    protected static ?string $slug = 'patrol-monitoring';

    public static function moduleId(): string
    {
        return 'patrol_monitoring';
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
            ->limit(50)
            ->get();
    }

    /**
     * @return Collection<int, PatrolEvent>
     */
    public function getRecentEventsProperty(): Collection
    {
        return PatrolEvent::query()
            ->with(['checkpoint', 'patrolTour'])
            ->orderByDesc('occurred_at')
            ->limit(50)
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runEvaluator')
                ->label('Run missed-checkpoint evaluator')
                ->action(function (): void {
                    $created = app(MissedCheckpointEvaluator::class)->evaluate();
                    Notification::make()
                        ->title('Evaluator finished')
                        ->body($created->count().' new missed-checkpoint event(s).')
                        ->success()
                        ->send();
                }),
        ];
    }
}
