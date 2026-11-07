<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ContractsSlas\Filament\Resources\ContractResource\Pages;
use App\Modules\ContractsSlas\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ContractResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Contracts';

    protected static ?string $modelLabel = 'Contract';

    protected static ?string $slug = 'contracts';

    public static function moduleId(): string
    {
        return 'contracts_slas';
    }

    public static function getNavigationGroup(): string
    {
        return 'Clients';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->required()
                ->searchable(),
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('reference')->maxLength(128),
            Forms\Components\DatePicker::make('starts_at'),
            Forms\Components\DatePicker::make('ends_at'),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\Repeater::make('rates')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('label')->required()->maxLength(128),
                    Forms\Components\TextInput::make('amount_cents')->numeric()->required(),
                    Forms\Components\TextInput::make('currency')->length(3)->default('USD')->required(),
                    Forms\Components\TextInput::make('rate_type')->maxLength(64),
                ])
                ->columnSpanFull()
                ->collapsible(),
            Forms\Components\Repeater::make('slas')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('target_metric')->required()->maxLength(128),
                    Forms\Components\TextInput::make('target_value')->numeric()->default(100),
                ])
                ->columnSpanFull()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('reference')->toggleable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('starts_at')->date(),
                Tables\Columns\TextColumn::make('ends_at')->date(),
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
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
