<?php

namespace App\Model\Donation\Entities\Donation\Values;

use App\Model\Common\Contracts\ValueContract;
use App\Model\Donation\Entities\Donation\Donation;
use Webmozart\Assert\Assert;

class Amount implements ValueContract
{
    private float $value;

    public function __construct(float $value)
    {
        Assert::numeric($value);
        Assert::greaterThan($value, Donation::MIN_PRICE - 1);
        $this->value = round($value, 2);
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
