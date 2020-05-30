<?php

namespace App\Model\Common\Services\Payment;

class TransactionConfig
{
    private string $id;
    private float $amount;
    private ?string $description;

    public function __construct(string $id, float $amount, ?string $description = null)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
