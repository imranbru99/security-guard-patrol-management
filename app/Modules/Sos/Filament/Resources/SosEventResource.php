<?php

declare(strict_types=1);

namespace App\Modules\Sos\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Sos\Filament\Resources\SosEventResource\Pages;
use App\Modules\Sos\Models\SosEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class SosEventResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = SosEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'SOS events';

    protected static ?string $slug = 'sos-events';

    public static function moduleId(): string
    {
        return 'sos';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('guard_id')
                ->relationship('guardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Guard'),
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('kind')
                ->options([
                    'panic' => 'Panic',
                    'man_down' => 'Man down',
                    'silent' => 'Silent',
                    'checkin_missed' => 'Check-in missed',
                ])
                ->required()
                ->default('panic'),
            Forms\Components\Toggle::make('silent')->default(false),
            Forms\Components\Toggle::make('drill_mode')->default(false),
            Forms\Components\Select::make('status')
                ->options([
                    'open' => 'Open',
                    'acknowledged' => 'Acknowledged',
                    'resolved' => 'Resolved',
                ])
                ->required()
                ->default('open'),
            Forms\Components\TextInput::make('latitude')->numeric()->nullable(),
            Forms\Components\TextInput::make('longitude')->numeric()->nullable(),
            Forms\Components\DateTimePicker::make('triggered_at')->nullable(),
            Forms\Components\Textarea::make('resolution_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kind')->badge(),
                Tables\Columns\TextColumn::make('guardEmployee.name')->label('Guard'),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\IconColumn::make('silent')->boolean(),
                Tables\Columns\IconColumn::make('drill_mode')->boolean(),
                Tables\Columns\TextColumn::make('triggered_at')->dateTime()->sortable(),
            ])
            ->defaultSort('triggered_at', 'desc')
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
            'index' => Pages\ListSosEvents::route('/'),
            'create' => Pages\CreateSosEvent::route('/create'),
            'edit' => Pages\EditSosEvent::route('/{record}/edit'),
        ];
    }
}
