<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier;

use Symfony\Component\Notifier\Message\MessageOptionsInterface;

final class FirebaseOptions implements MessageOptionsInterface
{
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    public function getRecipientId(): ?string
    {
        // TODO: Implement getRecipientId() method.
    }
}
