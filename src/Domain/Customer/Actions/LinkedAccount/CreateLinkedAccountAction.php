<?php

declare(strict_types=1);

namespace Domain\Customer\Actions\LinkedAccount;

use Domain\Customer\Models\LinkedAccount;

final class CreateLinkedAccountAction
{
    public static function execute(array $data): bool
    {
        // Create the Customer
        $link_account = LinkedAccount::updateOrCreate([
            'resource_id' => data_get(
                target: $data,
                key: 'data.attributes.resource_id',
            ),
        ], [
            'id' => data_get(
                target: $data,
                key: 'data.id',
            ),
            'resource_id' => data_get(
                target: $data,
                key: 'data.attributes.resource_id',
            ),
            'customer_id' => data_get(
                target: $data,
                key: 'data.included.customer.id',
            ),
            'scheme' => data_get(
                target: $data,
                key: 'data.attributes.scheme',
            ),
            'account_number' => data_get(
                target: $data,
                key: 'data.attributes.account_number',
            ),
            'status' => data_get(
                target: $data,
                key: 'data.attributes.status',
            ),
        ]);

        return (bool)$link_account;
    }
}
