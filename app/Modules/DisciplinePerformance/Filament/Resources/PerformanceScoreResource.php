<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource\Pages;
use App\Modules\DisciplinePerformance\Models\PerformanceScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class PerformanceScoreResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = PerformanceScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Performance scores';

    protected static ?string $modelLabel = 'Performance Score';

    protected static ?string $slug = 'performance-scores';

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
            Forms\Components\TextInput::make('period')->required()->maxLength(32)->helperText('e.g. 2026-Q1'),
            Forms\Components\TextInput::make('score')->numeric()->required()->minValue(0)->maxValue(100),
            Forms\Components\DatePicker::make('reviewed_on'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guard.name')->label('Guard')->searchable(),
                Tables\Columns\TextColumn::make('period')->sortable(),
                Tables\Columns\TextColumn::make('score')->sortable(),
                Tables\Columns\TextColumn::make('reviewed_on')->date()->sortable(),
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
            'index' => Pages\ListPerformanceScores::route('/'),
            'create' => Pages\CreatePerformanceScore::route('/create'),
            'edit' => Pages\EditPerformanceScore::route('/{record}/edit'),
        ];
    }
}
