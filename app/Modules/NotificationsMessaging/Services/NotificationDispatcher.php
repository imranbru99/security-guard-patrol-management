<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\NotificationsMessaging\Channels\ChannelDriver;
use App\Modules\NotificationsMessaging\Channels\FakeChannelDriver;
use App\Modules\NotificationsMessaging\Channels\LogChannelDriver;
use App\Modules\NotificationsMessaging\Models\NotificationLog;
use App\Modules\NotificationsMessaging\Models\NotificationTemplate;
use InvalidArgumentException;

final class NotificationDispatcher
{
    /** @var array<string, ChannelDriver> */
    private array $drivers;

    public function __construct()
    {
        $this->drivers = [
            'log' => new LogChannelDriver,
            'fake' => new FakeChannelDriver,
        ];
    }

    public function register(ChannelDriver $driver): void
    {
        $this->drivers[$driver->channel()] = $driver;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function dispatch(
        Tenant $tenant,
        string $templateKey,
        string $recipient,
        array $payload = [],
        ?string $channelOverride = null,
        ?int $ruleId = null,
    ): NotificationLog {
        /** @var NotificationTemplate|null $template */
        $template = NotificationTemplate::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('key', $templateKey)
            ->where('is_active', true)
            ->first();

        if ($template === null) {
            throw new InvalidArgumentException("Unknown or inactive template [{$templateKey}].");
        }

        $channel = $channelOverride ?? $template->channel;
        $driver = $this->drivers[$channel] ?? null;

        if ($driver === null) {
            throw new InvalidArgumentException("No channel driver registered for [{$channel}].");
        }

        $body = $this->render($template->body, $payload);
        $subject = $this->render((string) ($template->subject ?? ''), $payload);

        $result = $driver->send($recipient, [
            'subject' => $subject,
            'body' => $body,
            'template' => $template->key,
            'data' => $payload,
        ]);

        return NotificationLog::query()->create([
            'tenant_id' => $tenant->id,
            'template_id' => $template->id,
            'rule_id' => $ruleId,
            'channel' => $channel,
            'recipient' => $recipient,
            'payload' => $payload,
            'status' => $result['ok'] ? 'sent' : 'failed',
            'error' => $result['ok'] ? null : $result['message'],
            'sent_at' => $result['ok'] ? now() : null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function render(string $template, array $payload): string
    {
        return (string) preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function (array $matches) use ($payload): string {
            $key = $matches[1];

            return isset($payload[$key]) ? (string) $payload[$key] : '';
        }, $template);
    }
}
