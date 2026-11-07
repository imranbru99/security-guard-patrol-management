<?php

declare(strict_types=1);

namespace App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource\Pages;

use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
