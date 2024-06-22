<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier\Notification;

final class ApnsNotification
{
    public function __construct(
        private string $category,
    ) {
    }

    public function toArray(): array
    {
        return [
            'category' => $this->category,
        ];
    }
}
