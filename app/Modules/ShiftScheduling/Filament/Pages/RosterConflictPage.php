<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ShiftScheduling\DTOs\ConflictViolation;
use App\Modules\ShiftScheduling\Models\ShiftAssignment;
use App\Modules\ShiftScheduling\Services\RosterConflictEngine;
use Filament\Actions\Action;
use Filament\Pages\Page;

final class RosterConflictPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string $view = 'ShiftScheduling::filament.roster-conflicts';

    protected static ?string $navigationLabel = 'Roster Conflicts';

    protected static ?string $title = 'Roster Conflict Dry-Run';

    protected static ?string $slug = 'roster-conflicts';

    /** @var list<array<string, mixed>> */
    public array $violations = [];

    public float $minRestHours = 8.0;

    public static function moduleId(): string
    {
        return 'shift_scheduling';
    }

    public static function getNavigationGroup(): string
    {
        return 'Scheduling';
    }

    public function mount(): void
    {
        $this->runDryRun();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Re-run dry-run')
                ->action(fn () => $this->runDryRun()),
        ];
    }

    public function runDryRun(): void
    {
        $assignments = ShiftAssignment::query()
            ->with('shift')
            ->whereHas('shift')
            ->get()
            ->map(static function (ShiftAssignment $assignment): array {
                return [
                    'id' => (int) $assignment->id,
                    'guard_id' => (int) $assignment->guard_id,
                    'starts_at' => (string) $assignment->shift?->starts_at,
                    'ends_at' => (string) $assignment->shift?->ends_at,
                ];
            })
            ->filter(static fn (array $row): bool => $row['starts_at'] !== '' && $row['ends_at'] !== '')
            ->values()
            ->all();

        $engine = new RosterConflictEngine(minRestHours: $this->minRestHours);

        $this->violations = array_map(
            static fn (ConflictViolation $v): array => $v->toArray(),
            $engine->analyze($assignments)
        );
    }
}
