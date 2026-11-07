<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource\Pages;
use App\Modules\NotificationsMessaging\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class NotificationTemplateResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Templates';

    protected static ?string $modelLabel = 'Notification template';

    protected static ?string $slug = 'notification-templates';

    public static function moduleId(): string
    {
        return 'notifications';
    }

    public static function getNavigationGroup(): string
    {
        return 'Messaging';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')->required()->maxLength(100),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\Select::make('channel')
                ->options([
                    'log' => 'Log',
                    'fake' => 'Fake',
                    'mail' => 'Mail (future)',
                    'sms' => 'SMS (future)',
                ])
                ->required()
                ->default('log'),
            Forms\Components\TextInput::make('subject')->maxLength(255),
            Forms\Components\Textarea::make('body')->required()->rows(6)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('channel'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
