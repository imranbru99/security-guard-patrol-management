<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource\Pages;
use App\Modules\PublicApiWebhooks\Models\WebhookEndpoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class WebhookEndpointResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = WebhookEndpoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Webhooks';

    protected static ?string $modelLabel = 'Webhook Endpoint';

    protected static ?string $slug = 'webhook-endpoints';

    public static function moduleId(): string
    {
        return 'public_api';
    }

    public static function getNavigationGroup(): string
    {
        return 'Integrations';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->required()
                ->url()
                ->maxLength(2048)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('description')
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('events')
                ->helperText('e.g. incident.created, patrol.completed')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('secret')
                ->password()
                ->revealable()
                ->helperText('Leave blank to auto-generate on create'),
            Forms\Components\Toggle::make('active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('description')->toggleable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
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
            'index' => Pages\ListWebhookEndpoints::route('/'),
            'create' => Pages\CreateWebhookEndpoint::route('/create'),
            'edit' => Pages\EditWebhookEndpoint::route('/{record}/edit'),
        ];
    }
}
