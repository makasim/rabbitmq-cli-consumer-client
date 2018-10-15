<?php
namespace Makasim\RabbitmqCliConsumer;

use Interop\Amqp\Impl\AmqpMessage;
use Interop\Amqp\Impl\AmqpQueue;
use Interop\Amqp\Impl\AmqpTopic;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Destination;
use Interop\Queue\Exception\PurgeQueueNotSupportedException;
use Interop\Queue\Exception\SubscriptionConsumerNotSupportedException;
use Interop\Queue\Exception\TemporaryQueueNotSupportedException;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use Interop\Queue\Queue;
use Interop\Queue\SubscriptionConsumer;
use Interop\Queue\Topic;

class RabbitmqCliConsumerContext implements Context
{
    public function createMessage(string $body = '', array $properties = [], array $headers = []): Message
    {
        return new AmqpMessage($body, $properties, $headers);
    }

    public function createTopic(string $topicName): Topic
    {
        return new AmqpTopic($topicName);
    }

    public function createQueue(string $queueName): Queue
    {
        return new AmqpQueue($queueName);
    }

    public function createProducer(): Producer
    {
        throw new \LogicException('Producer is not supported');
    }

    public function createConsumer(Destination $destination): Consumer
    {
        return new RabbitmqCliConsumerConsumer($destination);
    }

    public function close(): void
    {
    }

    public function createTemporaryQueue(): Queue
    {
        throw TemporaryQueueNotSupportedException::providerDoestNotSupportIt();
    }

    public function createSubscriptionConsumer(): SubscriptionConsumer
    {
        throw SubscriptionConsumerNotSupportedException::providerDoestNotSupportIt();
    }

    public function purgeQueue(Queue $queue): void
    {
        throw PurgeQueueNotSupportedException::providerDoestNotSupportIt();
    }
}