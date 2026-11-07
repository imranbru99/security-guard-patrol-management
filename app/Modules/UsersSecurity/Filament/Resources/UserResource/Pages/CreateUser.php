<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Resources\UserResource\Pages;

use App\Core\Tenancy\TenantContext;
use App\Modules\UsersSecurity\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tenant_id'] = TenantContext::id();

        return $data;
    }
}
