<?php

declare(strict_types=1);

namespace App\Services;

use Anik\Amqp\Queues\Queue;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Anik\Amqp\Producer;
use Anik\Amqp\Producible;
use Anik\Amqp\ProducibleMessage;
use PhpAmqpLib\Message\AMQPMessage;
use Anik\Amqp\Exchanges\Exchange;

class AnikAmqpService
{
    protected AMQPStreamConnection $connection;

    protected \PhpAmqpLib\Channel\AMQPChannel $channel;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env(key: 'RABBITMQ_HOST'),
            env(key: 'RABBITMQ_PORT'),
            env(key: 'RABBITMQ_USER'),
            env(key: 'RABBITMQ_PASSWORD'),
            env(key: 'RABBITMQ_VHOST'),
            env(key: 'RABBITMQ_QUEUE_NAME')
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * @param string $exchange
     * @param string $type
     * @param string $queue
     * @param string $routingKey
     * @param array $data
     * @return void
     */
    public function publish(string $exchange, string $routingKey, array $data): void
    {
        $producer = new Producer($this->connection, $this->channel);
        $producer->publish(message: $this->producible($data), routingKey: $routingKey, exchange: $this->getExchange($exchange));
    }

    /**
     * @param $data
     * @return Producible
     */
    public function producible($data): Producible
    {
        return new ProducibleMessage(
            message: json_encode($data),
            properties: ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
    }

    /**
     * @param $exchange
     * @return Exchange
     */
    public function getExchange($exchange): Exchange
    {
        if ($exchange === 'ssb_direct'){
            return new Exchange(name: $exchange, type: Exchange::TYPE_DIRECT);
        }
        else {
            return new Exchange(name: $exchange, type: Exchange::TYPE_FANOUT);
        }
    }

    /**
     * @param $queue
     * @return Queue
     */
    public function getQueue($queue): Queue
    {
        return new Queue(name: $queue);
    }
}
