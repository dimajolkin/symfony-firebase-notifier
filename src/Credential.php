<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier;

use Google\Auth\CredentialsLoader;
use Google\Auth\FetchAuthTokenCache;
use Psr\Cache\CacheItemPoolInterface;

final class Credential
{
    private const SCOPES = [
        'https://www.googleapis.com/auth/firebase.messaging',
    ];

    private mixed $creds;
    private string $projectId;

    private function __construct(
        array $json,
    ) {
        if (!array_key_exists('project_id', $json)) {
            throw new \InvalidArgumentException('json key is missing the type field');
        }
        $this->projectId = (string) $json['project_id'];

        $this->creds = CredentialsLoader::makeCredentials(self::SCOPES, $json);
    }

    public static function fromServiceAccountContent(string $content): Credential
    {
        return new self(json_decode($content, true));
    }

    public static function fromServiceAccountBase64Content(string $content): Credential
    {
        return new self(json_decode(base64_decode($content, true), true));
    }

    public function getToken(?CacheItemPoolInterface $cache = null): Token
    {
        $creds = $this->creds;
        if ($cache !== null) {
            $creds = new FetchAuthTokenCache($this->creds, [], $cache);
        }

        /** @var array{access_token: string, expires_in: int, token_type: string} $token */
        $token = $creds->fetchAuthToken();
        if (isset($token['id_token'])) {
            throw new \Exception('');
        }

        return new Token($this->projectId, $token['access_token'], $token['token_type']);
    }
}
