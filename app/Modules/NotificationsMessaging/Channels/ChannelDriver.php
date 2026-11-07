<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Channels;

interface ChannelDriver
{
    public function channel(): string;

    /**
     * @param  array<string, mixed>  $payload
     * @return array{ok: bool, message: string}
     */
    public function send(string $recipient, array $payload): array;
}
