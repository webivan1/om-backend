<?php

namespace App\Model\Message\Entities\Message\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class MessageText implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = clean($value);

        Assert::notEmpty($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
