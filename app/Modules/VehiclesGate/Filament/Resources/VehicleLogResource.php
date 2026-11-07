<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource\Pages;
use App\Modules\VehiclesGate\Models\VehicleLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class VehicleLogResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = VehicleLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Vehicle logs';

    protected static ?string $modelLabel = 'Vehicle log';

    protected static ?string $slug = 'vehicle-logs';

    public static function moduleId(): string
    {
        return 'vehicles_gate';
    }

    public static function getNavigationGroup(): string
    {
        return 'Access';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('parking_slot_id')
                ->relationship('parkingSlot', 'code')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('plate_number')->required()->maxLength(64),
            Forms\Components\TextInput::make('vehicle_type')->maxLength(64),
            Forms\Components\TextInput::make('driver_name')->maxLength(255),
            Forms\Components\TextInput::make('driver_id_number')->maxLength(128),
            Forms\Components\TextInput::make('purpose')->maxLength(255),
            Forms\Components\Select::make('direction')
                ->options(['in' => 'In', 'out' => 'Out'])
                ->required()
                ->default('in'),
            Forms\Components\TextInput::make('weighbridge_in_kg')->numeric()->nullable(),
            Forms\Components\TextInput::make('weighbridge_out_kg')->numeric()->nullable(),
            Forms\Components\DateTimePicker::make('entered_at')->nullable(),
            Forms\Components\DateTimePicker::make('exited_at')->nullable(),
            Forms\Components\Toggle::make('violation'),
            Forms\Components\TextInput::make('violation_note')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('driver_name')->placeholder('—'),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->placeholder('—'),
                Tables\Columns\TextColumn::make('direction')->badge(),
                Tables\Columns\IconColumn::make('violation')->boolean(),
                Tables\Columns\TextColumn::make('entered_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('exited_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListVehicleLogs::route('/'),
            'create' => Pages\CreateVehicleLog::route('/create'),
            'edit' => Pages\EditVehicleLog::route('/{record}/edit'),
        ];
    }
}
