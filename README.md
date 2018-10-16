[Queue Interop](https://github.com/queue-interop/queue-interop) based wrapper for [rabbitmq-cli-consumer](https://github.com/corvus-ch/rabbitmq-cli-consumer).  

## Usage

```bash
composer makasim/php-fpm-queue:0.1.x-dev queue-interop/queue-interop:0.7.x-dev queue-interop/amqp-interop:0.8.x-dev
```

An executable:


```php
<?php
# executable.php

use Makasim\RabbitmqCliConsumer\RabbitmqCliConsumerConnectionFactory;

require_once __DIR__.'/vendor/autoload.php';

$context = (new RabbitmqCliConsumerConnectionFactory())->createContext();

$queue = $context->createQueue('aQueue');
$consumer = $context->createConsumer($queue);

if ($message = $consumer->receiveNoWait()) {
    // process message

    $consumer->acknowledge($message);
    
    // or
    //$consumer->reject($message);
}
```

## License

[MIT License](LICENSE)            
