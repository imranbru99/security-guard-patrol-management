<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource\Pages;
use App\Modules\LicencesDocuments\Models\LicenceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class LicenceTypeResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = LicenceType::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Licence types';

    protected static ?string $modelLabel = 'Licence Type';

    protected static ?string $slug = 'licence-types';

    public static function moduleId(): string
    {
        return 'licences';
    }

    public static function getNavigationGroup(): string
    {
        return 'People';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('code')->maxLength(64),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->sortable(),
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
            'index' => Pages\ListLicenceTypes::route('/'),
            'create' => Pages\CreateLicenceType::route('/create'),
            'edit' => Pages\EditLicenceType::route('/{record}/edit'),
        ];
    }
}
