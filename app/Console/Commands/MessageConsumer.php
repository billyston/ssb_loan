<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Domain\Customer\Actions\CreateCustomerAction;
use Illuminate\Console\Command;

final class MessageConsumer extends Command
{
    protected $signature = 'app:message-consumer';

    public function handle(): void
    {
        $rabbitMQService = new RabbitMQService;
        $rabbitMQService->consume(exchange: 'ssb_direct', type: 'direct', queue: 'loan', routingKey: 'ssb_loa', callback: function ($message) {

            $headers = $message->get('application_headers')->getNativeData();

            // Check the actions and call the right class
            if (data_get(target: $headers, key: 'action') === 'CreateCustomerAction'){
                $register = CreateCustomerAction::execute(
                    json_decode(
                        json: $message->getBody(),
                        associative: true
                    )
                );
                if ($register) $message->ack();
            }
        });
    }
}
