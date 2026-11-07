<?php

declare(strict_types=1);

namespace App\Modules\Billing\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Billing\Filament\Resources\InvoiceResource\Pages;
use App\Modules\Billing\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class InvoiceResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $slug = 'invoices';

    public static function moduleId(): string
    {
        return 'billing';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('number')->maxLength(64),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'approved' => 'Approved',
                    'sent' => 'Sent',
                    'paid' => 'Paid',
                    'void' => 'Void',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\TextInput::make('currency')->length(3)->default('USD')->required(),
            Forms\Components\TextInput::make('subtotal_cents')->numeric()->default(0),
            Forms\Components\TextInput::make('tax_cents')->numeric()->default(0),
            Forms\Components\TextInput::make('total_cents')->numeric()->default(0),
            Forms\Components\DatePicker::make('issued_on'),
            Forms\Components\DatePicker::make('due_on'),
            Forms\Components\Repeater::make('lines')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('description')->required(),
                    Forms\Components\TextInput::make('quantity')->numeric()->default(1),
                    Forms\Components\TextInput::make('unit_amount_cents')->numeric()->default(0),
                    Forms\Components\TextInput::make('amount_cents')->numeric()->default(0),
                ])
                ->columnSpanFull()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('total_cents')->label('Total (¢)'),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('issued_on')->date(),
                Tables\Columns\TextColumn::make('due_on')->date(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
