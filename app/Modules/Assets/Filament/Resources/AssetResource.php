<?php

declare(strict_types=1);

namespace App\Modules\Assets\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Assets\Filament\Resources\AssetResource\Pages;
use App\Modules\Assets\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class AssetResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Assets';

    protected static ?string $modelLabel = 'Asset';

    protected static ?string $slug = 'assets';

    public static function moduleId(): string
    {
        return 'assets';
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
            Forms\Components\TextInput::make('asset_tag')->required()->maxLength(64),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('category')->maxLength(128),
            Forms\Components\Select::make('status')
                ->options([
                    'available' => 'Available',
                    'assigned' => 'Assigned',
                    'maintenance' => 'Maintenance',
                    'retired' => 'Retired',
                ])
                ->required()
                ->default('available'),
            Forms\Components\TextInput::make('serial_number')->maxLength(128),
            Forms\Components\TextInput::make('qr_code')->maxLength(128),
            Forms\Components\Toggle::make('is_armed_equipment')->label('Armed equipment'),
            Forms\Components\TextInput::make('licence_permit_number')->maxLength(128),
            Forms\Components\DatePicker::make('licence_expires_on')->nullable(),
            Forms\Components\TextInput::make('daily_issue_count')->numeric()->default(0),
            Forms\Components\TextInput::make('daily_return_count')->numeric()->default(0),
            Forms\Components\Toggle::make('custody_discrepancy'),
            Forms\Components\TextInput::make('custody_holder')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset_tag')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\IconColumn::make('is_armed_equipment')->boolean()->label('Armed'),
                Tables\Columns\IconColumn::make('custody_discrepancy')->boolean()->label('Discrepancy'),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->placeholder('—'),
            ])
            ->defaultSort('asset_tag')
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
