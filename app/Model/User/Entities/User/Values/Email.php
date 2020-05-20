<?php

namespace App\Model\User\Entities\User\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Email implements ValueContract
{
    protected string $value;

    public function __construct(string $value)
    {
        Assert::email($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
