<?php

declare(strict_types=1);

namespace App\Modules\Expenses\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Expenses\Filament\Resources\ExpenseResource\Pages;
use App\Modules\Expenses\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ExpenseResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Expenses';

    protected static ?string $modelLabel = 'Expense';

    protected static ?string $slug = 'expenses';

    public static function moduleId(): string
    {
        return 'expenses';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')->searchable()->nullable(),
            Forms\Components\Select::make('purchase_order_id')->relationship('purchaseOrder', 'number')->searchable()->nullable(),
            Forms\Components\TextInput::make('amount_cents')->numeric()->required()->default(0),
            Forms\Components\TextInput::make('currency')->length(3)->default('USD')->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'submitted' => 'Submitted',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'reimbursed' => 'Reimbursed',
                ])
                ->default('submitted')
                ->required(),
            Forms\Components\TextInput::make('category')->maxLength(128),
            Forms\Components\DatePicker::make('incurred_on'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('amount_cents')->label('Amount (¢)'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('vendor.name')->label('Vendor'),
                Tables\Columns\TextColumn::make('incurred_on')->date(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
