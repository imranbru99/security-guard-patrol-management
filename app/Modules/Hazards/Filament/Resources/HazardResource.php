<?php

declare(strict_types=1);

namespace App\Modules\Hazards\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Hazards\Filament\Resources\HazardResource\Pages;
use App\Modules\Hazards\Models\Hazard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class HazardResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Hazard::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Hazards';

    protected static ?string $slug = 'hazards';

    public static function moduleId(): string
    {
        return 'hazards';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('reported_by_guard_id')
                ->relationship('reportedByGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Reported by'),
            Forms\Components\Select::make('assigned_to_user_id')
                ->relationship('assignedToUser', 'name')
                ->searchable()
                ->nullable()
                ->label('Assigned to'),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\Select::make('category')
                ->options([
                    'hazard' => 'Hazard',
                    'near_miss' => 'Near miss',
                    'defect' => 'Facility defect',
                    'maintenance' => 'Maintenance',
                ])
                ->required()
                ->default('hazard'),
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
                    'open' => 'Open',
                    'assigned' => 'Assigned',
                    'in_progress' => 'In progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ])
                ->required()
                ->default('open'),
            Forms\Components\DateTimePicker::make('reported_at')->nullable(),
            Forms\Components\DateTimePicker::make('resolved_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('severity')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('reported_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListHazards::route('/'),
            'create' => Pages\CreateHazard::route('/create'),
            'edit' => Pages\EditHazard::route('/{record}/edit'),
        ];
    }
}
