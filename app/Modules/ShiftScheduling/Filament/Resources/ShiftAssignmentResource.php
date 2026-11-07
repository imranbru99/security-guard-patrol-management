<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource\Pages;
use App\Modules\ShiftScheduling\Models\ShiftAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ShiftAssignmentResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = ShiftAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Shift Assignments';

    protected static ?string $modelLabel = 'Shift Assignment';

    protected static ?string $slug = 'shift-assignments';

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
            Forms\Components\Select::make('shift_id')
                ->relationship('shift', 'ulid')
                ->getOptionLabelFromRecordUsing(
                    fn ($record): string => ($record->label ?: $record->ulid).' · '.$record->starts_at?->format('Y-m-d H:i')
                )
                ->searchable()
                ->required(),
            Forms\Components\Select::make('guard_id')
                ->relationship('guardEmployee', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'assigned' => 'Assigned',
                    'confirmed' => 'Confirmed',
                    'declined' => 'Declined',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('assigned'),
            Forms\Components\DateTimePicker::make('assigned_at')->default(now()),
            Forms\Components\DateTimePicker::make('acknowledged_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guardEmployee.name')->label('Guard')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('shift.starts_at')->label('Starts')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('shift.ends_at')->label('Ends')->dateTime(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('assigned_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListShiftAssignments::route('/'),
            'create' => Pages\CreateShiftAssignment::route('/create'),
            'edit' => Pages\EditShiftAssignment::route('/{record}/edit'),
        ];
    }
}
