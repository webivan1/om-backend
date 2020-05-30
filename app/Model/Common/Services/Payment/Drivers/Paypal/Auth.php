<?php

namespace App\Model\Common\Services\Payment\Drivers\Paypal;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Auth
{
    private string $client;
    private string $secret;

    public function __construct(string $client, string $secret)
    {
        $this->client = $client;
        $this->secret = $secret;
    }

    public function auth(): ApiContext
    {
        return new ApiContext(new OAuthTokenCredential($this->client, $this->secret));
    }
}

