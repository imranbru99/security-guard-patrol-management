<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\GuardsEmployees\Filament\Resources\GuardResource\Pages;
use App\Modules\GuardsEmployees\Models\Guard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class GuardResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Guard::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Guards';

    protected static ?string $modelLabel = 'Guard';

    protected static ?string $slug = 'guards';

    public static function moduleId(): string
    {
        return 'guards';
    }

    public static function getNavigationGroup(): string
    {
        return 'People';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('employee_id')
                ->required()
                ->maxLength(64),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'on_leave' => 'On leave',
                    'terminated' => 'Terminated',
                ])
                ->required()
                ->default('active'),
            Forms\Components\Select::make('branch_id')
                ->relationship('branch', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('photo_path')
                ->maxLength(255),
            Forms\Components\TextInput::make('badge_qr')
                ->maxLength(255),
            Forms\Components\TagsInput::make('skills')
                ->columnSpanFull(),
            Forms\Components\KeyValue::make('emergency_contact')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('branch.name')->label('Branch'),
                Tables\Columns\TextColumn::make('badge_qr')->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListGuards::route('/'),
            'create' => Pages\CreateGuard::route('/create'),
            'edit' => Pages\EditGuard::route('/{record}/edit'),
        ];
    }
}
