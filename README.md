Adapted for using http

Close from : https://github.com/symfony/firebase-notifier

```php

include __DIR__ .'/../vendor/autoload.php';

use Dimajolkin\SymfonyFirebaseNotifier\Credential;
use Dimajolkin\SymfonyFirebaseNotifier\FirebaseTransport;
use Dimajolkin\SymfonyFirebaseNotifier\MessageType\TargetMessageOptions;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\AndroidNotification;
use Dimajolkin\SymfonyFirebaseNotifier\Notification\CommonNotification;
use Symfony\Component\Notifier\Chatter;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\ChatMessage;

$file = file_get_contents('......json');

$token = Credential::fromServiceAccountContent($file)->getToken();

$chatter = new Chatter(new FirebaseTransport($token));

$chatMessage = new ChatMessage('super text');

$options = new TargetMessageOptions(
    token: '....',
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



```
