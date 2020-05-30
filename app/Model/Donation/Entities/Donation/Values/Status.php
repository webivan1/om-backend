<?php

namespace App\Model\Donation\Entities\Donation\Values;

use App\Model\Common\Contracts\ValueContract;
use App\Model\Donation\Entities\Donation\Donation;
use Webmozart\Assert\Assert;

class Status implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::inArray($value, [
            Donation::STATUS_APPROVED,
            Donation::STATUS_REJECTED,
            Donation::STATUS_WAITING
        ]);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
