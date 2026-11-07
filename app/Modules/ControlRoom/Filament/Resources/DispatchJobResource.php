<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource\Pages;
use App\Modules\ControlRoom\Models\DispatchJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class DispatchJobResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = DispatchJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Dispatch jobs';

    protected static ?string $slug = 'dispatch-jobs';

    public static function moduleId(): string
    {
        return 'control_room';
    }

    public static function getNavigationGroup(): string
    {
        return 'Control';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('assigned_guard_id')
                ->relationship('assignedGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Assigned guard'),
            Forms\Components\Select::make('alert_type')
                ->options([
                    'sos' => 'SOS',
                    'missed_patrol' => 'Missed patrol',
                    'geofence' => 'Geofence',
                    'alarm' => 'Alarm',
                    'late_clock_in' => 'Late clock-in',
                ])
                ->required(),
            Forms\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'normal' => 'Normal',
                    'high' => 'High',
                    'critical' => 'Critical',
                ])
                ->required()
                ->default('normal'),
            Forms\Components\Select::make('status')
                ->options([
                    'queued' => 'Queued',
                    'acknowledged' => 'Acknowledged',
                    'dispatched' => 'Dispatched',
                    'closed' => 'Closed',
                ])
                ->required()
                ->default('queued'),
            Forms\Components\Textarea::make('summary')->columnSpanFull(),
            Forms\Components\TextInput::make('source_type')->nullable(),
            Forms\Components\TextInput::make('source_id')->numeric()->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alert_type')->badge(),
                Tables\Columns\TextColumn::make('priority')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('assignedGuardEmployee.name')->label('Guard'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchJobs::route('/'),
            'create' => Pages\CreateDispatchJob::route('/create'),
            'edit' => Pages\EditDispatchJob::route('/{record}/edit'),
        ];
    }
}
