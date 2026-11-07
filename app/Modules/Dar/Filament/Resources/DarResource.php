<?php

declare(strict_types=1);

namespace App\Modules\Dar\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Dar\Filament\Resources\DarResource\Pages;
use App\Modules\Dar\Models\Dar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class DarResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Dar::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'DARs';

    protected static ?string $modelLabel = 'DAR';

    protected static ?string $slug = 'dars';

    public static function moduleId(): string
    {
        return 'dar';
    }

    public static function getNavigationGroup(): string
    {
        return 'Safety';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('shift_id')->relationship('shift', 'ulid')->searchable()->nullable(),
            Forms\Components\Select::make('site_id')->relationship('site', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('guard_id')
                ->relationship('guardEmployee', 'name')
                ->searchable()
                ->nullable()
                ->label('Guard'),
            Forms\Components\DatePicker::make('report_date')->nullable(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'signed_off' => 'Signed off',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\Toggle::make('client_visible')->default(false),
            Forms\Components\DateTimePicker::make('signed_off_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site'),
                Tables\Columns\TextColumn::make('guardEmployee.name')->label('Guard'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\IconColumn::make('client_visible')->boolean(),
                Tables\Columns\TextColumn::make('signed_off_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListDars::route('/'),
            'create' => Pages\CreateDar::route('/create'),
            'edit' => Pages\EditDar::route('/{record}/edit'),
        ];
    }
}
