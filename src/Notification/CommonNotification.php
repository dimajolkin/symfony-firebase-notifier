<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier\Notification;

final class CommonNotification
{
    public function __construct(
        private string $title,
        private string $body,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}
