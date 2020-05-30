<?php

namespace App\Model\Donation\Entities\Donation\Values;

use App\Model\Common\Contracts\ValueContract;
use App\Model\Donation\Entities\Donation\Donation;
use Webmozart\Assert\Assert;

class Source implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::inArray($value, [
            Donation::SOURCE_TINKOFF,
            Donation::SOURCE_PAYPAL
        ]);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
