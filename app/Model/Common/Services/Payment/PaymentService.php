<?php

namespace App\Model\Common\Services\Payment;

class PaymentService
{
    private PaymentDriver $driver;

    public function __construct(PaymentDriver $driver)
    {
        $this->driver = $driver;
    }

    public function getTransactionId(array $request): string
    {
        return $this->driver->getDriver()->getId($request);
    }

    public function createTransaction(
        TransactionConfig $config,
        string $successUrl,
        string $failUrl
    ): ?string
    {
        return $this->driver->getDriver()->create($config, $successUrl, $failUrl);
    }

    public function checkTransaction(TransactionConfig $config, array $params)
    {
        return $this->driver->getDriver()->check($config, $params);
    }
}
