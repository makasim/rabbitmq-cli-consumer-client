<?php
namespace Makasim\RabbitmqCliConsumer;

use Enqueue\Dsn\Dsn;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;

class RabbitmqCliConsumerConnectionFactory implements ConnectionFactory
{
    /**
     * @var string
     */
    private $dsn;

    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function createContext(): Context
    {
        $dsn = new Dsn($this->dsn);
        if ('rabbitmq-cli-consumer' !== $dsn->getSchemeProtocol()) {
            throw new \LogicException('Protocol is not supported');
        }

        return new RabbitmqCliConsumerContext();
    }
}