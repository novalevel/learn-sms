# Learn-sms

The easiest way to send short message.

# Usage

```php
use Wwwp66650\LearnSms\LearnSms;

$config = [...];
$learnSms = new LearnSms($config);
$learnSms->gateway('Log')->send(1888888888, 'hello world!');
```

# License

MIT
