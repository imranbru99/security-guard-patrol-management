<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Incidents\Filament\Resources\IncidentResource\Pages;
use App\Modules\Incidents\Models\Incident;
use App\Modules\Incidents\Services\IncidentWorkflow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class IncidentResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Incident::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Incidents';

    protected static ?string $modelLabel = 'Incident';

    protected static ?string $slug = 'incidents';

    public static function moduleId(): string
    {
        return 'incidents';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('incident_type_id')
                ->relationship('incidentType', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('summary')->columnSpanFull(),
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('post_id')->relationship('post', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('shift_id')
                ->relationship('shift', 'ulid')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('reported_by_guard_id')
                ->relationship('reportedByGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Reported by'),
            Forms\Components\Select::make('assigned_to_guard_id')
                ->relationship('assignedToGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Assigned to'),
            Forms\Components\Select::make('severity')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'critical' => 'Critical',
                ])
                ->required()
                ->default('medium'),
            Forms\Components\Select::make('status')
                ->options(collect(Incident::STATUSES)->mapWithKeys(fn (string $s): array => [$s => str_replace('_', ' ', ucfirst($s))])->all())
                ->required()
                ->default('reported')
                ->disabled(fn (?Incident $record): bool => $record !== null),
            Forms\Components\DateTimePicker::make('occurred_at')->nullable(),
            Forms\Components\DateTimePicker::make('sla_due_at')->nullable(),
            Forms\Components\TextInput::make('police_report_no')->nullable(),
            Forms\Components\Toggle::make('injuries')->default(false),
            Forms\Components\TagsInput::make('tags')->columnSpanFull(),
            Forms\Components\Section::make('Evidence (immutable)')
                ->description('Media links to MediaVault. Evidence is immutable; access is custody-logged on view.')
                ->schema([
                    Forms\Components\Placeholder::make('evidence_note')
                        ->content('Attach MediaVault assets via IncidentMedia records. Do not mutate vault hashes after linking.'),
                ])
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('incidentType.name')->label('Type')->toggleable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('severity')->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('reportedByGuardEmployee.name')->label('Reporter')->toggleable(),
                Tables\Columns\TextColumn::make('occurred_at')->dateTime()->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transition')
                    ->label('Advance status')
                    ->icon('heroicon-o-arrow-path')
                    ->form(function (Incident $record): array {
                        $options = collect(app(IncidentWorkflow::class)->allowedTransitions($record))
                            ->mapWithKeys(fn (string $s): array => [$s => str_replace('_', ' ', ucfirst($s))])
                            ->all();

                        return [
                            Forms\Components\Select::make('to_status')
                                ->options($options)
                                ->required(),
                            Forms\Components\Textarea::make('note')->nullable(),
                        ];
                    })
                    ->action(function (Incident $record, array $data): void {
                        app(IncidentWorkflow::class)->transition(
                            $record,
                            (string) $data['to_status'],
                            $data['note'] ?? null,
                        );
                    })
                    ->visible(fn (Incident $record): bool => app(IncidentWorkflow::class)->allowedTransitions($record) !== []),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncidents::route('/'),
            'create' => Pages\CreateIncident::route('/create'),
            'view' => Pages\ViewIncident::route('/{record}'),
            'edit' => Pages\EditIncident::route('/{record}/edit'),
        ];
    }
}
