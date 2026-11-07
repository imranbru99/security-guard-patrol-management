<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\KeyControl\Filament\Resources\KeyItemResource\Pages;
use App\Modules\KeyControl\Models\KeyItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class KeyItemResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = KeyItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Keys';

    protected static ?string $modelLabel = 'Key';

    protected static ?string $slug = 'keys';

    public static function moduleId(): string
    {
        return 'key_control';
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
            Forms\Components\Select::make('post_id')
                ->relationship('post', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('code')->required()->maxLength(64),
            Forms\Components\TextInput::make('label')->required()->maxLength(255),
            Forms\Components\TextInput::make('location')->maxLength(255),
            Forms\Components\Select::make('status')
                ->options([
                    'available' => 'Available',
                    'issued' => 'Issued',
                    'lost' => 'Lost',
                    'retired' => 'Retired',
                ])
                ->required()
                ->default('available'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->placeholder('—'),
                Tables\Columns\TextColumn::make('location')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
            ])
            ->defaultSort('code')
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
            'index' => Pages\ListKeyItems::route('/'),
            'create' => Pages\CreateKeyItem::route('/create'),
            'edit' => Pages\EditKeyItem::route('/{record}/edit'),
        ];
    }
}
