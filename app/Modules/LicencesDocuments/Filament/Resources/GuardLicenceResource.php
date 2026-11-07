<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource\Pages;
use App\Modules\LicencesDocuments\Models\GuardLicence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class GuardLicenceResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = GuardLicence::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Guard licences';

    protected static ?string $modelLabel = 'Guard Licence';

    protected static ?string $slug = 'guard-licences';

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
            Forms\Components\Select::make('guard_id')
                ->relationship('guardEmployee', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('licence_type_id')
                ->relationship('licenceType', 'name')
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('number')
                ->required()
                ->maxLength(128),
            Forms\Components\DatePicker::make('expires_at'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guard.name')->label('Guard')->searchable(),
                Tables\Columns\TextColumn::make('licenceType.name')->label('Type'),
                Tables\Columns\TextColumn::make('number')->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable()
                    ->color(fn (GuardLicence $record): ?string => $record->expires_at !== null && $record->expires_at->isPast()
                        ? 'danger'
                        : ($record->expires_at !== null && $record->expires_at->lte(now()->addDays(30)) ? 'warning' : null)),
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
            'index' => Pages\ListGuardLicences::route('/'),
            'create' => Pages\CreateGuardLicence::route('/create'),
            'edit' => Pages\EditGuardLicence::route('/{record}/edit'),
        ];
    }
}
