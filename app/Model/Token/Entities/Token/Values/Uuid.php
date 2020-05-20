<?php

namespace App\Model\Token\Entities\Token\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Uuid implements ValueContract
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
