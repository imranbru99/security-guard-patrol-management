<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Channels;

use Illuminate\Support\Facades\Log;

final class LogChannelDriver implements ChannelDriver
{
    public function channel(): string
    {
        return 'log';
    }

    public function send(string $recipient, array $payload): array
    {
        Log::info('notifications.log_channel', [
            'recipient' => $recipient,
            'payload' => $payload,
        ]);

        return ['ok' => true, 'message' => 'Logged'];
    }
}
