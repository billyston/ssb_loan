<?php

declare(strict_types=1);

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class ExternalService
{
    public string $base_url;
    public string $api_key;

    public function __construct()
    {
        $this->base_url = config(key: 'services.susubox.ssb_external.base_url');
        $this->api_key = config(key: 'services.susubox.ssb_external.api_key');
    }

    public function accountCreated(array $data): void
    {
        Http::withHeaders(['Content-Type' => 'application/vnd.api+json', 'Accept' => 'application/vnd.api+json'])->post(
            url: $this->base_url.'enquiries/mobile-money/subscriber',
            data: $data
        )->json();
    }
}
