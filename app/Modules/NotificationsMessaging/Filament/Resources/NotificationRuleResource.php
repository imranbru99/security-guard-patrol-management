<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource\Pages;
use App\Modules\NotificationsMessaging\Models\NotificationRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class NotificationRuleResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = NotificationRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Rules';

    protected static ?string $modelLabel = 'Notification rule';

    protected static ?string $slug = 'notification-rules';

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
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('event')->required()->maxLength(255)
                ->helperText('Domain event name, e.g. incident.created'),
            Forms\Components\Select::make('template_id')
                ->relationship('template', 'name')
                ->required()
                ->searchable(),
            Forms\Components\Select::make('channel')
                ->options([
                    'log' => 'Log',
                    'fake' => 'Fake',
                    'mail' => 'Mail (future)',
                    'sms' => 'SMS (future)',
                ])
                ->required()
                ->default('log'),
            Forms\Components\KeyValue::make('conditions')->nullable(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('event')->searchable(),
                Tables\Columns\TextColumn::make('template.name')->label('Template'),
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
            'index' => Pages\ListNotificationRules::route('/'),
            'create' => Pages\CreateNotificationRule::route('/create'),
            'edit' => Pages\EditNotificationRule::route('/{record}/edit'),
        ];
    }
}
