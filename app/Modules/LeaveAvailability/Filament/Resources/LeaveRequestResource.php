<?php

declare(strict_types=1);

namespace App\Modules\LeaveAvailability\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource\Pages;
use App\Modules\LeaveAvailability\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class LeaveRequestResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Leave requests';

    protected static ?string $modelLabel = 'Leave Request';

    protected static ?string $slug = 'leave-requests';

    public static function moduleId(): string
    {
        return 'leave';
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
            Forms\Components\Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->searchable()
                ->required(),
            Forms\Components\DatePicker::make('starts_on')->required(),
            Forms\Components\DatePicker::make('ends_on')->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('pending'),
            Forms\Components\Textarea::make('reason')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guard.name')->label('Guard')->searchable(),
                Tables\Columns\TextColumn::make('leaveType.name')->label('Type'),
                Tables\Columns\TextColumn::make('starts_on')->date()->sortable(),
                Tables\Columns\TextColumn::make('ends_on')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
