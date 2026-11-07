<?php

declare(strict_types=1);

namespace App\Modules\Emergency\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource\Pages;
use App\Modules\Emergency\Models\EmergencyPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class EmergencyPlanResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = EmergencyPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationLabel = 'Emergency plans';

    protected static ?string $slug = 'emergency-plans';

    public static function moduleId(): string
    {
        return 'emergency';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('body')->columnSpanFull(),
            Forms\Components\TagsInput::make('muster_points')->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListEmergencyPlans::route('/'),
            'create' => Pages\CreateEmergencyPlan::route('/create'),
            'edit' => Pages\EditEmergencyPlan::route('/{record}/edit'),
        ];
    }
}
