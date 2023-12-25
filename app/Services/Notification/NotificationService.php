<?php

declare(strict_types=1);

namespace App\Services\Notification;

use Domain\Customer\Models\Customer;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public string $base_url;
    public string $api_key;

    public function __construct()
    {
        $this->base_url = config(key: 'services.susubox.ssb_notification.base_url');
        $this->api_key = config(key: 'services.susubox.ssb_notification.api_key');
    }

    public function accountCreated(Customer $customer, array $data): void
    {
        Http::withHeaders(['Content-Type' => 'application/vnd.api+json', 'Accept' => 'application/vnd.api+json'])->post(
            url: $this->base_url.'customers/'.$customer->resource_id.'/susu/personal',
            data: $data
        )->json();
    }
}
