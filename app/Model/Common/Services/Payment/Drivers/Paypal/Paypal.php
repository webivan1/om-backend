<?php

namespace App\Model\Common\Services\Payment\Drivers\Paypal;

use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Webmozart\Assert\Assert;
use App\Model\Common\Services\Payment\Drivers\Exceptions\FailPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\SuccessPaymentException;
use App\Model\Common\Services\Payment\Contracts\DriverContract;
use App\Model\Common\Services\Payment\TransactionConfig;

class Paypal implements DriverContract
{
    private function getContext(): ApiContext
    {
        $auth = new Auth(env('PAYPAL_CLIENT'), env('PAYPAL_SECRET'));
        $context = $auth->auth();

        $context->setConfig([
            'mode' => env('PAYPAL_MODE')
        ]);

        return $context;
    }

    public function getId(array $request): string
    {
        $this->validate($request);

        return $this->getItemNumber($request);
    }

    public function create(TransactionConfig $config, string $successUrl, string $failUrl): string
    {
        $context = $this->getContext();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($config->getDescription() ?? 'payment');
        $item->setCurrency(env('PAYPAL_CURRENCY'));
        $item->setQuantity(1);
        $item->setSku($config->getId());
        $item->setPrice($config->getAmount());

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency(env('PAYPAL_CURRENCY'));
        $amount->setTotal($amount);

        $transaction = new Transaction();
        $transaction->setItemList($itemList);
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($successUrl)
            ->setCancelUrl($failUrl);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($context);
            return $payment->getApprovalLink();
        } catch (PayPalConnectionException $e) {
            Log::error($e->getMessage());
            throw new \DomainException($e->getMessage());
        }
    }

    public function check(TransactionConfig $config, array $request): void
    {
        $this->validate($request);

        $paymentId = $request['paymentId'];
        $payerId = $request['PayerID'];

        $context = $this->getContext();

        $payment = Payment::get($paymentId, $context);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        $transaction = new Transaction();
        $amount = new Amount();

        $amount->setCurrency(env('PAYPAL_CURRENCY'));
        $amount->setTotal($config->getAmount());

        $transaction->setAmount($amount);

        // Add the above transaction object inside our Execution object.
        $execution->addTransaction($transaction);

        $result = $payment->execute($execution, $context);

        if ($result->getState() !== 'approved') {
            Log::error('Transaction PAYPAL ERROR ' . $config->getId());
            throw new FailPaymentException('This transaction is not approved');
        }

        throw new SuccessPaymentException();
    }

    private function validate(array $params): void
    {
        $id = $this->getItemNumber($params);
        $paymentId = $params['paymentId'] ?? '';
        $payerId = $params['PayerID'] ?? '';

        Assert::notEmpty($id);
        Assert::notEmpty($paymentId);
        Assert::notEmpty($payerId);
    }

    private function getItemNumber(array $params): string
    {
        return (string) ($params['item_number'] ?? $params['item_number1'] ?? '');
    }
}
