<?php

namespace App\Model\Donation\Entities\Donation\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Email implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
