<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\SettingsForms\Filament\Resources\FormTemplateResource\Pages;
use App\Modules\SettingsForms\Models\FormTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

final class FormTemplateResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = FormTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Form Templates';

    protected static ?string $modelLabel = 'Form Template';

    protected static ?string $slug = 'form-templates';

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
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\Textarea::make('schema')
                ->label('Schema (JSON)')
                ->rows(8)
                ->columnSpanFull()
                ->helperText('Field keys referenced from custom fields; full builder comes later.')
                ->formatStateUsing(fn ($state): string => is_array($state) ? (string) json_encode($state, JSON_PRETTY_PRINT) : (string) $state)
                ->dehydrateStateUsing(function (?string $state): array {
                    if ($state === null || trim($state) === '') {
                        return [];
                    }
                    $decoded = json_decode($state, true);

                    return is_array($decoded) ? $decoded : [];
                }),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
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
            'index' => Pages\ListFormTemplates::route('/'),
            'create' => Pages\CreateFormTemplate::route('/create'),
            'edit' => Pages\EditFormTemplate::route('/{record}/edit'),
        ];
    }
}
