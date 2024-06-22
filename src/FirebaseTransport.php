<?php

declare(strict_types=1);

namespace Dimajolkin\SymfonyFirebaseNotifier;

use InvalidArgumentException;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class FirebaseTransport extends AbstractTransport
{
    protected const HOST = 'fcm.googleapis.com/v1/projects/{projectId}/messages:send';

    public function __construct(
        private Token $token,
        ?HttpClientInterface $client = null,
        ?EventDispatcherInterface $dispatcher = null,
    ) {
        parent::__construct($client, $dispatcher);
    }

    public function __toString(): string
    {
        return sprintf('firebase://%s', $this->getEndpoint());
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof ChatMessage) {
            throw new UnsupportedMessageTypeException(__CLASS__, ChatMessage::class, $message);
        }

        $endpoint = sprintf('https://%s', $this->getEndpoint());
        $options = $message->getOptions()?->toArray() ?? [];

        $response = $this->client->request('POST', $endpoint, [
            'headers' => [
                'Authorization' => sprintf('%s %s', $this->token->getType(), $this->token->getAccessToken()),
            ],
            'json' => $options,
        ]);

        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new TransportException('Could not reach the remote Firebase server.', $response, 0, $e);
        }

        $contentType = $response->getHeaders(false)['content-type'][0] ?? '';
        $jsonContents = str_starts_with($contentType, 'application/json') ? $response->toArray(false) : null;
        $errorMessage = null;

        if ($jsonContents && isset($jsonContents['results'][0]['error'])) {
            $errorMessage = $jsonContents['results'][0]['error'];
        } elseif (200 !== $statusCode) {
            $errorMessage = $response->getContent(false);
        }

        if (null !== $errorMessage) {
            throw new TransportException('Unable to post the Firebase message: '.$errorMessage, $response);
        }

        $success = $response->toArray(false);

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($success['results'][0]['message_id'] ?? '');

        return $sentMessage;
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof ChatMessage && (null === $message->getOptions() || $message->getOptions() instanceof FirebaseOptions);
    }

    protected function getDefaultHost(): string
    {
        return strtr(parent::getDefaultHost(), ['{projectId}' =>  $this->token->getProjectId()]);
    }
}
