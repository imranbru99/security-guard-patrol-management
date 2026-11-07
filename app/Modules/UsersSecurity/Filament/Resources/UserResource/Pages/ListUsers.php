<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Resources\UserResource\Pages;

use App\Modules\UsersSecurity\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
