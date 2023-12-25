<?php

declare(strict_types=1);

namespace App\Services\Customer;

use Illuminate\Support\Facades\Http;

class CustomerService
{
    public string $base_url;
    public string $api_key;

    public function __construct()
    {
        $this->base_url = config(key: 'services.susubox.ssb_customer.base_url');
        $this->api_key = config(key: 'services.susubox.ssb_customer.api_key');
    }

    public function pinValidation(string $customer_resource, array $request): array
    {
        return Http::withHeaders(['Content-Type' => 'application/vnd.api+json', 'Accept' => 'application/vnd.api+json'])->post(
            url: config(key: 'services.susubox.ssb_customer.base_url').$customer_resource.'/pin-check',
            data: $request,
        )->json();
    }
}
