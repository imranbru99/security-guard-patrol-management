<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Microsite\Filament\Resources\TenantPageResource\Pages;
use App\Modules\Microsite\Models\TenantPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class TenantPageResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = TenantPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Microsite pages';

    protected static ?string $modelLabel = 'Microsite page';

    protected static ?string $slug = 'microsite-pages';

    public static function moduleId(): string
    {
        return 'microsite';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->maxLength(255),
            Forms\Components\Select::make('page_type')
                ->options([
                    'home' => 'Home',
                    'services' => 'Services',
                    'contact' => 'Contact',
                    'careers' => 'Careers',
                    'page' => 'Page',
                ])
                ->default('page')
                ->required(),
            Forms\Components\Textarea::make('body')->columnSpanFull(),
            Forms\Components\Toggle::make('is_published')->default(false),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('page_type')->badge(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenantPages::route('/'),
            'create' => Pages\CreateTenantPage::route('/create'),
            'edit' => Pages\EditTenantPage::route('/{record}/edit'),
        ];
    }
}
