<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\LeadsCrm\Filament\Resources\QuoteResource\Pages;
use App\Modules\LeadsCrm\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class QuoteResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Quotes';

    protected static ?string $modelLabel = 'Quote';

    protected static ?string $slug = 'quotes';

    public static function moduleId(): string
    {
        return 'leads_crm';
    }

    public static function getNavigationGroup(): string
    {
        return 'Clients';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Select::make('lead_id')
                ->relationship('lead', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->searchable()
                ->nullable(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'sent' => 'Sent',
                    'accepted' => 'Accepted',
                    'declined' => 'Declined',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\TextInput::make('currency')->length(3)->default('USD')->required(),
            Forms\Components\Repeater::make('items')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('description')->required()->maxLength(255),
                    Forms\Components\TextInput::make('quantity')->numeric()->default(1)->required(),
                    Forms\Components\TextInput::make('amount_cents')->numeric()->required(),
                ])
                ->columnSpanFull()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lead.name')->label('Lead'),
                Tables\Columns\TextColumn::make('client.name')->label('Client'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('items_count')->counts('items')->label('Items'),
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
