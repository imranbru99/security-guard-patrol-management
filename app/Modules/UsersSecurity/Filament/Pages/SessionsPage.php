<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

final class SessionsPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static string $view = 'UsersSecurity::filament.sessions';

    protected static ?string $navigationLabel = 'Sessions';

    protected static ?string $title = 'Active sessions';

    protected static ?string $slug = 'sessions';

    /** @var list<array{id: string, user_email: string|null, ip_address: string|null, last_activity: int}> */
    public array $sessions = [];

    public static function moduleId(): string
    {
        return 'users_security';
    }

    public static function getNavigationGroup(): string
    {
        return 'Security';
    }

    public function mount(): void
    {
        $tenant = TenantContext::require();
        $userIds = User::query()->where('tenant_id', $tenant->id)->pluck('id');

        $this->sessions = DB::table('sessions')
            ->whereIn('user_id', $userIds)
            ->orderByDesc('last_activity')
            ->limit(100)
            ->get()
            ->map(function (object $row): array {
                $email = User::query()->where('id', $row->user_id)->value('email');

                return [
                    'id' => (string) $row->id,
                    'user_email' => $email !== null ? (string) $email : null,
                    'ip_address' => $row->ip_address !== null ? (string) $row->ip_address : null,
                    'last_activity' => (int) $row->last_activity,
                ];
            })
            ->all();
    }
}
