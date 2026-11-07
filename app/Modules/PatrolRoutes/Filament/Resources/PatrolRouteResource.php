<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource\Pages;
use App\Modules\PatrolRoutes\Models\PatrolRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class PatrolRouteResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = PatrolRoute::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Patrol routes';

    protected static ?string $modelLabel = 'Patrol Route';

    protected static ?string $slug = 'patrol-routes';

    public static function moduleId(): string
    {
        return 'patrol_routes';
    }

    public static function getNavigationGroup(): string
    {
        return 'Patrols';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\Toggle::make('is_ordered')->default(true)
                ->helperText('When on, guards must scan checkpoints in sequence'),
            Forms\Components\Toggle::make('randomised')->default(false),
            Forms\Components\TextInput::make('min_dwell_seconds')->numeric(),
            Forms\Components\TextInput::make('max_dwell_seconds')->numeric(),
            Forms\Components\TextInput::make('grace_minutes')->numeric()->default(10)->required(),
            Forms\Components\TextInput::make('gps_radius_meters')->numeric()->default(75)->required(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Repeater::make('routeCheckpoints')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('checkpoint_id')
                        ->relationship('checkpoint', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('sequence')->numeric()->default(1)->required(),
                    Forms\Components\TextInput::make('expected_minutes_from_start')->numeric(),
                ])
                ->orderColumn('sequence')
                ->columnSpanFull()
                ->collapsible(),
            Forms\Components\Repeater::make('schedules')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('interval_minutes')->numeric()->default(60)->required(),
                    Forms\Components\TimePicker::make('window_start')->seconds(false),
                    Forms\Components\TimePicker::make('window_end')->seconds(false),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])
                ->columnSpanFull()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\IconColumn::make('is_ordered')->boolean()->label('Ordered'),
                Tables\Columns\TextColumn::make('grace_minutes')->label('Grace (min)'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
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
            'index' => Pages\ListPatrolRoutes::route('/'),
            'create' => Pages\CreatePatrolRoute::route('/create'),
            'edit' => Pages\EditPatrolRoute::route('/{record}/edit'),
        ];
    }
}
