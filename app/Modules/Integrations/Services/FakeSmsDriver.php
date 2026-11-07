<?php

declare(strict_types=1);

namespace App\Modules\Integrations\Services;

final class FakeSmsDriver
{
    /** @var list<array{to: string, body: string, sent_at: string}> */
    private array $sent = [];

    /**
     * @return array{ok: bool, driver: string, to: string, id: string}
     */
    public function send(string $to, string $body): array
    {
        $id = 'fake_sms_'.bin2hex(random_bytes(4));
        $this->sent[] = [
            'to' => $to,
            'body' => $body,
            'sent_at' => now()->toIso8601String(),
            'id' => $id,
        ];

        return [
            'ok' => true,
            'driver' => 'fake',
            'to' => $to,
            'id' => $id,
        ];
    }

    /**
     * @return list<array{to: string, body: string, sent_at: string}>
     */
    public function sent(): array
    {
        return $this->sent;
    }
}
