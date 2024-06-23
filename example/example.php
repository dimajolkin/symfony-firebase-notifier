<?php

include __DIR__ .'/../vendor/autoload.php';

use Dimajolkin\SymfonyFirebaseNotifier\Credential;
use Dimajolkin\SymfonyFirebaseNotifier\FirebaseTransport;
use Dimajolkin\SymfonyFirebaseNotifier\MessageType\TargetMessageOptions;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\AndroidNotification;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\CommonNotification;
use Symfony\Component\Notifier\Chatter;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\ChatMessage;

$file = base64_encode(file_get_contents('moydom-92cd9-30da455ccdef.json'));

$token = Credential::fromServiceAccountBase64Content($file)->getToken();

$chatter = new Chatter(new FirebaseTransport($token));

$chatMessage = new ChatMessage('super text');

$validUserToken = 'coGZPev5I0T_r5CSde6DMk:APA91bGz_Mb_Kv01tRJc18Nerhc-DTHxaxthD9BQbz5QMzpu3gzJWq6qEyNCqdIq3CWDPtm8WTxc34JeRkTA2s1XamvFzBVlNVrHILYf4QrqhPiR4-8fnFADAiYXwjKot2UpQKWmolh7';
$invalidUserToken = 'ftmH2AqHzENrv3se4663wx:APA91bE2iBfpzC-NFijaSfOvCCYCWvud10OsDvpFOk9ZA3Mk4UY_fbSx4k7BNIjJb6EUzPPslcxi7L7iU3KtFhwWuCs_PrjXxboVvr0urUQn25KPIO96lkFny76WJ6GMeIL1RoAEX3bw';

$options = new TargetMessageOptions(
    token: $invalidUserToken,
    common: new CommonNotification(
      title: 'incident title',
      body: 'test message',
    ),
    android: new AndroidNotification(
        clickAction: 'open_incident_view',
    ),
    data: [
        'id' => '54841',
        'type' => 'incident',
        'title' => 'incident title',
        'message' => 'incident message',
    ],
);

// Add the custom options to the chat message and send the message
$chatMessage->options($options);
try {
    $chatter->send($chatMessage);
} catch (TransportException $exception) {
    echo $exception->getMessage();
}


