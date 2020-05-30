<?php

namespace App\Model\Donation\Entities\Donation\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Username implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
