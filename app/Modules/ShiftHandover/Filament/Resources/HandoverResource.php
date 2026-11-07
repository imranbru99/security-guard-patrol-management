<?php

declare(strict_types=1);

namespace App\Modules\ShiftHandover\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ShiftHandover\Filament\Resources\HandoverResource\Pages;
use App\Modules\ShiftHandover\Models\Handover;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class HandoverResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Handover::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Handovers';

    protected static ?string $modelLabel = 'Handover';

    protected static ?string $slug = 'handovers';

    public static function moduleId(): string
    {
        return 'handover';
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
                ->nullable(),
            Forms\Components\Select::make('from_guard_id')
                ->relationship('fromGuardEmployee', 'name')
                ->searchable()
                ->required()
                ->label('From guard'),
            Forms\Components\Select::make('to_guard_id')
                ->relationship('toGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('To guard'),
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('post_id')
                ->relationship('post', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
            Forms\Components\TagsInput::make('open_items')->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'acknowledged' => 'Acknowledged',
                    'closed' => 'Closed',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\DateTimePicker::make('handed_over_at')->nullable(),
            Forms\Components\DateTimePicker::make('acknowledged_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromGuardEmployee.name')->label('From')->searchable(),
                Tables\Columns\TextColumn::make('toGuardEmployee.name')->label('To')->placeholder('—'),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('handed_over_at')->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('acknowledged_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListHandovers::route('/'),
            'create' => Pages\CreateHandover::route('/create'),
            'edit' => Pages\EditHandover::route('/{record}/edit'),
        ];
    }
}
