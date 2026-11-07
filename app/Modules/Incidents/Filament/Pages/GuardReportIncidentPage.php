<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Incidents\Models\Incident;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

final class GuardReportIncidentPage extends Page implements HasForms
{
    use HasModuleGate;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string $view = 'Incidents::filament.guard-report-incident';

    protected static ?string $navigationLabel = 'Report incident';

    protected static ?string $title = 'Report incident';

    protected static ?string $slug = 'report-incident';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function moduleId(): string
    {
        return 'incidents';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public function mount(): void
    {
        $this->form->fill([
            'severity' => 'medium',
            'title' => '',
            'summary' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\Textarea::make('summary')->rows(4)->columnSpanFull(),
                Forms\Components\Select::make('severity')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'critical' => 'Critical',
                    ])
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $incident = Incident::query()->create([
            'title' => $state['title'],
            'summary' => $state['summary'] ?? null,
            'severity' => $state['severity'],
            'status' => 'reported',
            'occurred_at' => now(),
            'meta' => ['source' => 'guard_panel'],
        ]);

        Notification::make()
            ->title('Incident reported')
            ->body('Reference: '.$incident->ulid)
            ->success()
            ->send();

        $this->form->fill([
            'severity' => 'medium',
            'title' => '',
            'summary' => '',
        ]);
    }
}
