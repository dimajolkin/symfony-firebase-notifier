<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier;

final class Token
{
    public function __construct(
        private string $projectId,
        #[\SensitiveParameter]
        private string $accessToken,
        private string $type,
    ) {
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
