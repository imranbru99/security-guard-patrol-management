<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource\Pages;
use App\Modules\TasksPostOrders\Models\TaskItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class TaskItemResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = TaskItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Tasks';

    protected static ?string $modelLabel = 'Task';

    protected static ?string $slug = 'tasks';

    public static function moduleId(): string
    {
        return 'tasks_post_orders';
    }

    public static function getNavigationGroup(): string
    {
        return 'Access';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('post_id')
                ->relationship('post', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('assigned_guard_id')
                ->relationship('assignedGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Assigned guard'),
            Forms\Components\Select::make('post_order_id')
                ->relationship('postOrder', 'title')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\TagsInput::make('checklist')->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->options([
                    'open' => 'Open',
                    'in_progress' => 'In progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('open'),
            Forms\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'normal' => 'Normal',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ])
                ->required()
                ->default('normal'),
            Forms\Components\Toggle::make('is_recurring'),
            Forms\Components\TextInput::make('recurrence_rule')->maxLength(128),
            Forms\Components\DateTimePicker::make('due_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('assignedGuardEmployee.name')->label('Guard')->placeholder('—'),
                Tables\Columns\TextColumn::make('post.name')->label('Post')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('priority')->badge(),
                Tables\Columns\TextColumn::make('due_at')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListTaskItems::route('/'),
            'create' => Pages\CreateTaskItem::route('/create'),
            'edit' => Pages\EditTaskItem::route('/{record}/edit'),
        ];
    }
}
