<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Catalog\Filament\Resources\CatalogItemResource\Pages;
use App\Modules\Catalog\Models\CatalogItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class CatalogItemResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = CatalogItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Catalog items';

    protected static ?string $modelLabel = 'Catalog item';

    protected static ?string $slug = 'catalog-items';

    public static function moduleId(): string
    {
        return 'catalog';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('catalog_category_id')
                ->relationship('category', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->maxLength(255),
            Forms\Components\TextInput::make('sku')->maxLength(64),
            Forms\Components\Select::make('item_type')
                ->options([
                    'service' => 'Service',
                    'equipment' => 'Equipment',
                ])
                ->required()
                ->default('service'),
            Forms\Components\Textarea::make('summary')->columnSpanFull(),
            Forms\Components\Textarea::make('body')->columnSpanFull(),
            Forms\Components\TextInput::make('price_cents')->numeric()->required()->default(0),
            Forms\Components\TextInput::make('currency')->length(3)->default('USD')->required(),
            Forms\Components\Select::make('ai_status')
                ->options([
                    'draft' => 'Draft',
                    'reviewed' => 'Reviewed',
                    'published' => 'Published',
                ])
                ->default('draft')
                ->required(),
            Forms\Components\Toggle::make('is_published')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('item_type')->badge(),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\TextColumn::make('price_cents')->label('Price (¢)'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('ai_status')->badge(),
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
            'index' => Pages\ListCatalogItems::route('/'),
            'create' => Pages\CreateCatalogItem::route('/create'),
            'edit' => Pages\EditCatalogItem::route('/{record}/edit'),
        ];
    }
}
