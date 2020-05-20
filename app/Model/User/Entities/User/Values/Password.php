<?php

namespace App\Model\User\Entities\User\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Password implements ValueContract
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
