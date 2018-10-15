<?php
namespace Makasim\RabbitmqCliConsumer;

use Interop\Amqp\AmqpQueue;
use Interop\Amqp\Impl\AmqpMessage;
use Interop\Queue\Consumer;
use Interop\Queue\Message;
use Interop\Queue\Queue;

class RabbitmqCliConsumerConsumer implements Consumer
{
    /**
     * @var AmqpQueue
     */
    private $destination;

    public function __construct(AmqpQueue $destination)
    {
        $this->destination = $destination;
    }

    public function getQueue(): Queue
    {
        return $this->destination;
    }

    public function receive(int $timeout = 0): ?Message
    {
        return $this->receiveNoWait();
    }

    public function receiveNoWait(): ?Message
    {

        if (array_key_exists(1, $_SERVER['argv'])) {
            $input = $_SERVER['argv'][1];

            $data = json_decode(base64_decode($input));

            $headers = $data->properties;
            $properties = $headers['application_headers'];
            unset($headers['application_headers']);
            $deliveryInfo = $data->delivery_info;
            $body = $data->body;
        } else {
            $metadata = file_get_contents("php://fd/3");
            if (false === $metadata) {
                fwrite(STDERR, "failed to read metadata from fd3\n");
                exit(1);
            }

            $metadata = json_decode($metadata, true);
            if (JSON_ERROR_NONE != json_last_error()) {
                fwrite(STDERR, "failed to decode metadata\n");
                fwrite(STDERR, json_last_error_msg() . PHP_EOL);
                exit(1);
            }

            $headers = $metadata->properties;
            $properties = $headers['application_headers'];
            unset($headers['application_headers']);
            $deliveryInfo = $metadata->delivery_info;



// Read the body from STDIN.
            $body = file_get_contents("php://stdin");
            if (false === $body) {
                fwrite(STDERR, "failed to read body from STDIN\n");
                exit(1);
            }
        }

        $message = new AmqpMessage($body, $properties, $headers);
        $message->setRedelivered($deliveryInfo['redelivered']);
        $message->setDeliveryTag($deliveryInfo['delivery_tag']);
        $message->setRoutingKey($deliveryInfo['routing_key']);

        return $message;
    }

    public function acknowledge(Message $message): void
    {
        $this->exit(0);
    }

    public function reject(Message $message, bool $requeue = false): void
    {
        $this->exit($requeue ? 4 : 3);
    }

    private function exit(int $code): void
    {
        exit($code);
    }
}
