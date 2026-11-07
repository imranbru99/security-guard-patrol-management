<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftResource\Pages;
use App\Modules\ShiftScheduling\Models\Shift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ShiftResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Shift::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Shifts';

    protected static ?string $modelLabel = 'Shift';

    protected static ?string $slug = 'shifts';

    public static function moduleId(): string
    {
        return 'shift_scheduling';
    }

    public static function getNavigationGroup(): string
    {
        return 'Scheduling';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('roster_id')
                ->relationship('roster', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('shift_template_id')
                ->relationship('shiftTemplate', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('post_id')
                ->relationship('post', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\DateTimePicker::make('starts_at')->required(),
            Forms\Components\DateTimePicker::make('ends_at')->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'open' => 'Open',
                    'filled' => 'Filled',
                    'cancelled' => 'Cancelled',
                    'locked' => 'Locked',
                ])
                ->required()
                ->default('open'),
            Forms\Components\TextInput::make('label')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable()->placeholder('—'),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('post.name')->label('Post'),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('assignments_count')
                    ->counts('assignments')
                    ->label('Assigned'),
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
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShift::route('/create'),
            'edit' => Pages\EditShift::route('/{record}/edit'),
        ];
    }
}
