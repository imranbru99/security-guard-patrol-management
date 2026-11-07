<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

use Illuminate\Database\Eloquent\Model;

/**
 * Base Eloquent model for tenant-scoped domain entities.
 */
abstract class TenantModel extends Model
{
    use BelongsToTenant;
}
