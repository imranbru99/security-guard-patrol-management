<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource\Pages;
use App\Modules\AttendanceTracking\Models\AttendanceSummary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class AttendanceSummaryResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = AttendanceSummary::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Attendance Summaries';

    protected static ?string $modelLabel = 'Attendance Summary';

    protected static ?string $slug = 'attendance-summaries';

    public static function moduleId(): string
    {
        return 'attendance';
    }

    public static function getNavigationGroup(): string
    {
        return 'Scheduling';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('guard_id')
                ->relationship('guardEmployee', 'name')
                ->searchable()
                ->required(),
            Forms\Components\DatePicker::make('work_date')->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'PRESENT' => 'Present',
                    'LATE' => 'Late',
                    'HALF_DAY' => 'Half day',
                    'ABSENT' => 'Absent',
                ])
                ->required()
                ->default('ABSENT'),
            Forms\Components\DateTimePicker::make('first_in')->nullable(),
            Forms\Components\DateTimePicker::make('last_out')->nullable(),
            Forms\Components\TextInput::make('worked_minutes')->numeric()->default(0),
            Forms\Components\TextInput::make('late_minutes')->numeric()->default(0),
            Forms\Components\TextInput::make('early_leave_minutes')->numeric()->default(0),
            Forms\Components\TextInput::make('overtime_minutes')->numeric()->default(0),
            Forms\Components\TextInput::make('punches_count')->numeric()->default(0),
            Forms\Components\TextInput::make('shift_name')->maxLength(255),
            Forms\Components\TextInput::make('source')->default('hub')->maxLength(64),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guardEmployee.name')->label('Guard')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('work_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('first_in')->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('last_out')->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('worked_minutes')->label('Worked (min)'),
                Tables\Columns\TextColumn::make('late_minutes')->label('Late'),
                Tables\Columns\TextColumn::make('overtime_minutes')->label('OT'),
                Tables\Columns\TextColumn::make('source')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('work_date', 'desc')
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
            'index' => Pages\ListAttendanceSummaries::route('/'),
            'create' => Pages\CreateAttendanceSummary::route('/create'),
            'edit' => Pages\EditAttendanceSummary::route('/{record}/edit'),
        ];
    }
}
