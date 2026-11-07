<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Visitors\Filament\Resources\VisitResource\Pages;
use App\Modules\Visitors\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class VisitResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Visits';

    protected static ?string $modelLabel = 'Visit';

    protected static ?string $slug = 'visits';

    public static function moduleId(): string
    {
        return 'visitors';
    }

    public static function getNavigationGroup(): string
    {
        return 'Access';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('visitor_id')
                ->relationship('visitor', 'full_name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('host_name')->maxLength(255),
            Forms\Components\TextInput::make('host_email')->email()->maxLength(255),
            Forms\Components\TextInput::make('purpose')->maxLength(255),
            Forms\Components\Select::make('status')
                ->options([
                    'pre_registered' => 'Pre-registered',
                    'checked_in' => 'Checked in',
                    'checked_out' => 'Checked out',
                    'cancelled' => 'Cancelled',
                    'denied' => 'Denied',
                ])
                ->required()
                ->default('pre_registered'),
            Forms\Components\TextInput::make('badge_code')->maxLength(32),
            Forms\Components\DateTimePicker::make('expected_at')->nullable(),
            Forms\Components\DateTimePicker::make('checked_in_at')->nullable(),
            Forms\Components\DateTimePicker::make('checked_out_at')->nullable(),
            Forms\Components\DateTimePicker::make('expected_out_at')->nullable(),
            Forms\Components\Toggle::make('watchlist_hit')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visitor.full_name')->label('Visitor')->searchable(),
                Tables\Columns\TextColumn::make('host_name')->placeholder('—'),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\IconColumn::make('watchlist_hit')->boolean()->label('Watchlist'),
                Tables\Columns\TextColumn::make('badge_code')->toggleable(),
                Tables\Columns\TextColumn::make('checked_in_at')->dateTime()->toggleable(),
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
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }
}
