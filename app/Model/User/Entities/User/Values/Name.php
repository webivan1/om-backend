<?php

namespace App\Model\User\Entities\User\Values;

use Webmozart\Assert\Assert;

class Name
{
    protected string $value;

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
