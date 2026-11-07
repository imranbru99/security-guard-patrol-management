<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Listeners;

use App\Core\Tenancy\TenantContext;
use App\Modules\NotificationsMessaging\Models\NotificationRule;
use App\Modules\NotificationsMessaging\Services\NotificationDispatcher;
use Illuminate\Support\Facades\Log;

/**
 * Stub listener: wire concrete domain events to notification rules later.
 */
final class DispatchNotificationOnEvent
{
    public function __construct(
        private readonly NotificationDispatcher $dispatcher,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handle(object|string $event, array $payload = []): void
    {
        $tenant = TenantContext::get();
        if ($tenant === null) {
            return;
        }

        if (is_string($event)) {
            $eventName = $event;
        } else {
            $eventName = property_exists($event, 'eventName')
                ? (string) $event->eventName
                : $event::class;
        }

        $rules = NotificationRule::query()
            ->where('event', $eventName)
            ->where('is_active', true)
            ->with('template')
            ->get();

        foreach ($rules as $rule) {
            if ($rule->template === null) {
                continue;
            }

            try {
                $recipient = (string) ($payload['recipient'] ?? 'ops@tenant.local');
                $this->dispatcher->dispatch(
                    $tenant,
                    $rule->template->key,
                    $recipient,
                    $payload,
                    $rule->channel,
                    $rule->id,
                );
            } catch (\Throwable $e) {
                Log::warning('notifications.listener_failed', [
                    'rule' => $rule->ulid,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
