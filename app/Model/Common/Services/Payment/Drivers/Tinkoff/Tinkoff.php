<?php

namespace App\Model\Common\Services\Payment\Drivers\Tinkoff;

use App\Model\Common\Services\Payment\Contracts\DriverContract;
use App\Model\Common\Services\Payment\Drivers\Exceptions\FailResponseTextPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\SuccessResponseTextPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\WaitPaymentException;
use App\Model\Common\Services\Payment\TransactionConfig;
use Webmozart\Assert\Assert;

class Tinkoff implements DriverContract
{
    public function getId(array $request): string
    {
        $this->validate($request);
        return $this->getItemNumber($request);
    }

    public function create(TransactionConfig $config, string $successUrl, string $failUrl): string
    {
        $service = new TinkoffMerchantAPI(env('TINKOFF_TERMINAL'), env('TINKOFF_PASSWORD'));

        $status = $service->init([
            'Amount' => $config->getAmount() * 100,
            'OrderId' => $config->getId(),
            'Description' => $config->getDescription() ?? 'OurRights Donation',
            'Language' => 'ru',
            'NotificationURL' => $successUrl,
            'SuccessURL' => $successUrl,
            'FailURL' => $failUrl
        ]);

        if ($status) {
            return $service->paymentUrl;
        }

        throw new \DomainException('Pay failed');
    }

    public function check(TransactionConfig $config, array $request): ?string
    {
        $this->validate($request);

        $service = new TinkoffMerchantAPI(env('TINKOFF_TERMINAL'), env('TINKOFF_PASSWORD'));

        if ($service->checkToken($request)) {
            if ($request['Status'] === 'CONFIRMED') {
                throw new SuccessResponseTextPaymentException('OK');
            } else if (in_array($request['Status'], ['REVERSED', 'REFUNDED', 'PARTIAL_REFUNDED', 'REJECTED'])) {
                throw new FailResponseTextPaymentException('OK');
            }
        } else {
            throw new WaitPaymentException();
        }
    }

    private function validate(array $params): void
    {
        Assert::notEmpty($params['Status']);
    }

    private function getItemNumber(array $params): string
    {
        return (string) ($params['OrderId'] ?? '');
    }
}
