<?php

declare(strict_types=1);

namespace App\Modules\Inspections\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Inspections\Filament\Resources\InspectionResource\Pages;
use App\Modules\Inspections\Models\Inspection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class InspectionResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Inspection::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Inspections';

    protected static ?string $modelLabel = 'Inspection';

    protected static ?string $slug = 'inspections';

    public static function moduleId(): string
    {
        return 'inspections';
    }

    public static function getNavigationGroup(): string
    {
        return 'Access';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('inspection_template_id')
                ->relationship('template', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('inspector_guard_id')
                ->relationship('inspectorGuardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Inspector'),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'in_progress' => 'In progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\TextInput::make('score')->numeric()->nullable(),
            Forms\Components\TextInput::make('max_score')->numeric()->nullable(),
            Forms\Components\DateTimePicker::make('scheduled_at')->nullable(),
            Forms\Components\DateTimePicker::make('completed_at')->nullable(),
            Forms\Components\Toggle::make('client_visible'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('template.name')->label('Template'),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('score')->placeholder('—'),
                Tables\Columns\TextColumn::make('max_score')->placeholder('—'),
                Tables\Columns\TextColumn::make('completed_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListInspections::route('/'),
            'create' => Pages\CreateInspection::route('/create'),
            'edit' => Pages\EditInspection::route('/{record}/edit'),
        ];
    }
}
