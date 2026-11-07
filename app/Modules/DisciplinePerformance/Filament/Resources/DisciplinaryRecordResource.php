<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource\Pages;
use App\Modules\DisciplinePerformance\Models\DisciplinaryRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class DisciplinaryRecordResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = DisciplinaryRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Disciplinary records';

    protected static ?string $modelLabel = 'Disciplinary Record';

    protected static ?string $slug = 'disciplinary-records';

    public static function moduleId(): string
    {
        return 'discipline';
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
            Forms\Components\Select::make('severity')
                ->options([
                    'note' => 'Note',
                    'warning' => 'Warning',
                    'suspension' => 'Suspension',
                    'termination' => 'Termination',
                ])
                ->required()
                ->default('warning'),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\DatePicker::make('occurred_on'),
            Forms\Components\TextInput::make('outcome')->maxLength(255),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guard.name')->label('Guard')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('severity')->badge()->sortable(),
                Tables\Columns\TextColumn::make('occurred_on')->date()->sortable(),
                Tables\Columns\TextColumn::make('outcome')->toggleable(),
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
            'index' => Pages\ListDisciplinaryRecords::route('/'),
            'create' => Pages\CreateDisciplinaryRecord::route('/create'),
            'edit' => Pages\EditDisciplinaryRecord::route('/{record}/edit'),
        ];
    }
}
