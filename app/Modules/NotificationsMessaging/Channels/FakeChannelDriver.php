<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Channels;

final class FakeChannelDriver implements ChannelDriver
{
    /** @var list<array{recipient: string, payload: array<string, mixed>}> */
    public static array $sent = [];

    public function channel(): string
    {
        return 'fake';
    }

    public function send(string $recipient, array $payload): array
    {
        self::$sent[] = [
            'recipient' => $recipient,
            'payload' => $payload,
        ];

        return ['ok' => true, 'message' => 'Fake sent'];
    }

    public static function reset(): void
    {
        self::$sent = [];
    }
}
