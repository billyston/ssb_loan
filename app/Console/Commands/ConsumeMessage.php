<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Anik\Amqp\ConsumableMessage;
use Anik\Laravel\Amqp\Facades\Amqp;
use App\Services\AnikAmqpService;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class ConsumeMessage extends Command
{
    protected $signature = 'app:consume-message';

    protected $description = 'Command description';

    /**
     * @return void
     */
    public function handle(): void
    {
//        $AnikAmqpService = new AnikAmqpService();
//        Amqp::connection(name: 'rabbitmq')->consume(function(ConsumableMessage $data){
//            logger($data->getMessageBody());
//            $data->ack();
//        }, bindingKey: 'ssb_ext', exchange: $AnikAmqpService->getExchange(exchange: 'ssb_direct'), queue: $AnikAmqpService->getQueue(queue: 'external'));

        $rabbitMQService = new RabbitMQService;
        $rabbitMQService->consume(exchange: 'ssb_direct', type: 'direct', queue: 'susu', routingKey: 'ssb_sus', callback: function ($message) {

            logger(message: $message->getBody());

            $message->ack();
        });
    }
}
