<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\SettingsForms\Filament\Resources\CustomFieldResource\Pages;
use App\Modules\SettingsForms\Models\CustomField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class CustomFieldResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = CustomField::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Custom Fields';

    protected static ?string $modelLabel = 'Custom Field';

    protected static ?string $slug = 'custom-fields';

    public static function moduleId(): string
    {
        return 'settings_forms';
    }

    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('entity_type')
                ->required()
                ->maxLength(100)
                ->helperText('e.g. incident, guard, visitor'),
            Forms\Components\TextInput::make('key')
                ->required()
                ->maxLength(100)
                ->alphaDash(),
            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('type')
                ->required()
                ->options([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'number' => 'Number',
                    'select' => 'Select',
                    'checkbox' => 'Checkbox',
                    'date' => 'Date',
                ])
                ->default('text'),
            Forms\Components\TagsInput::make('options')
                ->helperText('For select fields'),
            Forms\Components\Toggle::make('required')
                ->default(false),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity_type')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('key')->searchable(),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\IconColumn::make('required')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
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
            'index' => Pages\ListCustomFields::route('/'),
            'create' => Pages\CreateCustomField::route('/create'),
            'edit' => Pages\EditCustomField::route('/{record}/edit'),
        ];
    }
}
