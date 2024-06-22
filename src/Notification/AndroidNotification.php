<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier\Notification;

final class AndroidNotification
{
    public function __construct(
        private string $clickAction,
        private ?string $body = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'click_action' => $this->clickAction,
        ];

        if ($this->body !== null) {
            $data['body'] = $this->body;
        }

        return  $data;
    }
}
