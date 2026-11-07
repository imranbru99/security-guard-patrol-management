<?php

declare(strict_types=1);

namespace App\Modules\Alarms\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Alarms\Filament\Resources\AlarmResource\Pages;
use App\Modules\Alarms\Models\Alarm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class AlarmResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Alarm::class;

    protected static ?string $navigationIcon = 'heroicon-o-speaker-wave';

    protected static ?string $navigationLabel = 'Alarms';

    protected static ?string $slug = 'alarms';

    public static function moduleId(): string
    {
        return 'alarms';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\TextInput::make('external_id')->nullable(),
            Forms\Components\Select::make('source')
                ->options([
                    'webhook' => 'Webhook',
                    'email' => 'Email',
                    'api' => 'API',
                ])
                ->required()
                ->default('webhook'),
            Forms\Components\TextInput::make('alarm_type')->nullable(),
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
                ->options([
                    'received' => 'Received',
                    'acknowledged' => 'Acknowledged',
                    'responding' => 'Responding',
                    'closed' => 'Closed',
                ])
                ->required()
                ->default('received'),
            Forms\Components\TextInput::make('outcome_code')->nullable(),
            Forms\Components\Toggle::make('false_alarm')->default(false),
            Forms\Components\TextInput::make('sla_seconds')->numeric()->nullable()->label('SLA (seconds)'),
            Forms\Components\TextInput::make('response_seconds')->numeric()->nullable(),
            Forms\Components\Toggle::make('sla_breached')->default(false),
            Forms\Components\DateTimePicker::make('received_at')->nullable(),
            Forms\Components\DateTimePicker::make('responded_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alarm_type')->searchable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('severity')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('sla_seconds')->label('SLA s'),
                Tables\Columns\TextColumn::make('response_seconds')->label('Response s'),
                Tables\Columns\IconColumn::make('sla_breached')->boolean(),
                Tables\Columns\IconColumn::make('false_alarm')->boolean(),
                Tables\Columns\TextColumn::make('received_at')->dateTime()->sortable(),
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
            'index' => Pages\ListAlarms::route('/'),
            'create' => Pages\CreateAlarm::route('/create'),
            'edit' => Pages\EditAlarm::route('/{record}/edit'),
        ];
    }
}
