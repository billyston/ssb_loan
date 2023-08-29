<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
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
     * Publishes a message to the specified topic.
     */
    public function publish(string $exchange, string $type, string $queue, string $routingKey, array $data): void
    {
        $this->channel->queue_declare(
            queue: $queue,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: $type,
            durable: true,
            auto_delete: false
        );
        $this->channel->queue_bind($queue, $exchange);
        $message = new AMQPMessage(
            body: $data,
            properties: array(
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'routingKey' => $routingKey
            ),
        );
        $this->channel->basic_publish(
            msg: $message,
            exchange: $exchange,
            routing_key: $routingKey
        );
    }

    /**
     * Consumes messages from the specified topic.
     */
    public function consume(string $exchange, string $type, string $queue, string $routingKey, callable $callback): void
    {
        $this->channel->queue_declare(
            queue: $queue,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: $type,
            durable: true,
            auto_delete: false
        );
        $this->channel->queue_bind(
            queue: $queue,
            exchange: $exchange,
            routing_key: $routingKey
        );
        $this->channel->basic_consume(
            queue: $queue,
            consumer_tag: '',
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * Closes the channel and connection when the object is destructed.
     *
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
