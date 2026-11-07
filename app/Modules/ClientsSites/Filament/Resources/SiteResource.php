<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ClientsSites\Filament\Resources\SiteResource\Pages;
use App\Modules\ClientsSites\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class SiteResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Sites';

    protected static ?string $modelLabel = 'Site';

    protected static ?string $slug = 'sites';

    public static function moduleId(): string
    {
        return 'clients_sites';
    }

    public static function getNavigationGroup(): string
    {
        return 'Clients';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->required()
                ->searchable(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')
                ->maxLength(255)
                ->helperText('Auto-generated from name when left blank'),
            Forms\Components\TextInput::make('address')->maxLength(512)->columnSpanFull(),
            Forms\Components\TextInput::make('latitude')->numeric(),
            Forms\Components\TextInput::make('longitude')->numeric(),
            Forms\Components\Textarea::make('geofence_polygon')
                ->label('Geofence polygon (JSON)')
                ->helperText('Paste a GeoJSON polygon coordinate array')
                ->columnSpanFull()
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                ->dehydrateStateUsing(function ($state) {
                    if ($state === null || $state === '') {
                        return null;
                    }
                    if (is_array($state)) {
                        return $state;
                    }

                    $decoded = json_decode((string) $state, true);

                    return is_array($decoded) ? $decoded : null;
                }),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Repeater::make('zones')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('code')->maxLength(64),
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
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->sortable(),
                Tables\Columns\TextColumn::make('address')->limit(40)->toggleable(),
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
