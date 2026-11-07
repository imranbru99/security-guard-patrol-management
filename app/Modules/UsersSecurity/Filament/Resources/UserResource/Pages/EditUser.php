<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Resources\UserResource\Pages;

use App\Modules\UsersSecurity\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
