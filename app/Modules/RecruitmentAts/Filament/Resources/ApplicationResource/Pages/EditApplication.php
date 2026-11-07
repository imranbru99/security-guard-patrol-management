<?php

declare(strict_types=1);

namespace App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource\Pages;

use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
