<?php

namespace App\Model\Common\Services\Payment\Contracts;

use App\Model\Common\Services\Payment\TransactionConfig;

interface DriverContract
{
    public function getId(array $request): string;

    /**
     * @param TransactionConfig $config
     * @param string $successUrl
     * @param string $failUrl
     * @return string url or redirect to pay url
     */
    public function create(
        TransactionConfig $config,
        string $successUrl,
        string $failUrl
    ): string;

    public function check(TransactionConfig $config, array $request);
}
