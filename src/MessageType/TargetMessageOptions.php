<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier\MessageType;

use Dimajolkin\SymfonyFirebaseNotifier\Notification\AndroidNotification;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\ApnsNotification;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\CommonNotification;
use Symfony\Component\Notifier\Message\MessageOptionsInterface;

final class TargetMessageOptions implements MessageOptionsInterface
{
    /**
     * @param array<array-key, string|int> $data
     */
    public function __construct(
        private string $token,
        private CommonNotification $common,
        private ?AndroidNotification $android = null,
        private ?ApnsNotification $apns = null,
        private array $data = [],
    ) {
    }

    public function toArray(): array
    {
        $message = [
            "token" => $this->token,
            "notification" => $this->common->toArray(),
        ];

        if ($this->data) {
            $message['data'] = array_map(fn (string|int $value) => (string) $value, $this->data);
        }

        if ($this->android !== null) {
            $message['android'] = [
                'notification' => $this->android->toArray(),
            ];
        }

        if ($this->apns !== null) {
            $message['apns'] = [
                'payload' => [
                    'aps' => $this->apns,
                ],
            ];
        }

        return [
            "message" => $message,
        ];
    }

    public function getRecipientId(): ?string
    {
        return $this->token;
    }
}
