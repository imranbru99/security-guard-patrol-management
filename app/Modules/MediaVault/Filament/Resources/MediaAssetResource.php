<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\MediaVault\Filament\Resources\MediaAssetResource\Pages;
use App\Modules\MediaVault\Models\MediaAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class MediaAssetResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = MediaAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Media Vault';

    protected static ?string $modelLabel = 'Media Asset';

    protected static ?string $slug = 'media-assets';

    public static function moduleId(): string
    {
        return 'media_vault';
    }

    public static function getNavigationGroup(): string
    {
        return 'Platform';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('filename')->disabled(),
            Forms\Components\TextInput::make('mime')->disabled(),
            Forms\Components\TextInput::make('size')->disabled(),
            Forms\Components\TextInput::make('sha256')->disabled()->columnSpanFull(),
            Forms\Components\TextInput::make('disk')->disabled(),
            Forms\Components\TextInput::make('path')->disabled()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mime')->toggleable(),
                Tables\Columns\TextColumn::make('size')->sortable(),
                Tables\Columns\TextColumn::make('sha256')->limit(12)->tooltip(fn (MediaAsset $record): string => $record->sha256),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaAssets::route('/'),
            'view' => Pages\ViewMediaAsset::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
